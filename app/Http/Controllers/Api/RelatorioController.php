<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estoque;
use App\Models\Farmacia;
use App\Models\Produto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Relatórios",
 *     description="Relatórios e análises de dados"
 * )
 */
class RelatorioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/relatorios/estoques-em-falta",
     *     summary="Relatório de produtos em falta",
     *     tags={"Relatórios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de estoques em falta")
     * )
     */
    public function estoquesEmFalta(Request $request): JsonResponse
    {
        $estoques = Estoque::with(['farmacia', 'produto'])
            ->whereRaw('quantidade < stock_minimo');

        if ($request->has('farmacia_id')) {
            $estoques->where('farmacia_id', $request->farmacia_id);
        }

        $data = $estoques->paginate($request->get('per_page', 15));

        return response()->json([
            'title' => 'Produtos em Falta',
            'total_em_falta' => $estoques->count(),
            'data' => $data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/relatorios/produtos-vencidos",
     *     summary="Relatório de produtos vencidos",
     *     tags={"Relatórios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de produtos vencidos")
     * )
     */
    public function produtosVencidos(Request $request): JsonResponse
    {
        $produtos = Produto::with(['categoria', 'estoques.farmacia'])
            ->where('data_validade', '<', now()->toDateString())
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'title' => 'Produtos Vencidos',
            'total_vencidos' => $produtos->count(),
            'data' => $produtos
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/relatorios/resumo",
     *     summary="Resumo geral da farmácia",
     *     tags={"Relatórios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Dados de resumo")
     * )
     */
    public function resumo(Request $request): JsonResponse
    {
        $farmacia_id = $request->get('farmacia_id');

        $query_estoque = Estoque::query();
        $query_produto = Produto::query();

        if ($farmacia_id) {
            $query_estoque->where('farmacia_id', $farmacia_id);
        }

        $total_produtos = $query_produto->count();
        $total_estoques = $query_estoque->count();
        $estoques_em_falta = $query_estoque->whereRaw('quantidade < stock_minimo')->count();
        $valor_total_estoque = $query_estoque->with('produto')
            ->get()
            ->sum(fn($e) => $e->quantidade * $e->produto->preco);

        return response()->json([
            'periodo' => [
                'inicio' => now()->startOfMonth()->toDateString(),
                'fim' => now()->endOfMonth()->toDateString(),
            ],
            'resumo' => [
                'total_produtos' => $total_produtos,
                'total_registros_estoque' => $total_estoques,
                'estoques_em_falta' => $estoques_em_falta,
                'valor_total_estoque' => number_format($valor_total_estoque, 2, '.', ''),
            ],
            'alertas' => [
                'produtos_vencidos' => Produto::where('data_validade', '<', now())->count(),
                'estoques_criticos' => $estoques_em_falta,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/relatorios/estoque-por-farmacia",
     *     summary="Relatório de estoque por farmácia",
     *     tags={"Relatórios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Resumo de estoque por farmácia")
     * )
     */
    public function estoquePorFarmacia(): JsonResponse
    {
        $farmacias = Farmacia::with(['estoques.produto'])
            ->get()
            ->map(function ($farmacia) {
                $total_estoque = $farmacia->estoques->sum('quantidade');
                $valor_total = $farmacia->estoques->sum(fn($e) => $e->quantidade * $e->produto->preco);
                $em_falta = $farmacia->estoques->filter(fn($e) => $e->quantidade < $e->stock_minimo)->count();

                return [
                    'id' => $farmacia->id,
                    'nome' => $farmacia->nome,
                    'total_itens_estoque' => $total_estoque,
                    'valor_total_estoque' => number_format($valor_total, 2, '.', ''),
                    'registros_em_falta' => $em_falta,
                ];
            });

        return response()->json([
            'total_farmacias' => $farmacias->count(),
            'data' => $farmacias
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/relatorios/movimentacao",
     *     summary="Relatório de movimentação de estoque",
     *     tags={"Relatórios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Histórico de movimentação")
     * )
     */
    public function movimentacao(Request $request): JsonResponse
    {
        // Este endpoint seria expandido com tabela de movimentações
        // Por enquanto, retorna sugestão

        return response()->json([
            'message' => 'Para histórico completo, adicione tabela "movimentacoes_estoque"',
            'estrutura_sugerida' => [
                'id',
                'estoque_id',
                'tipo' => 'repor|remover|ajuste',
                'quantidade',
                'usuario_id',
                'observacao',
                'created_at'
            ]
        ]);
    }
}
