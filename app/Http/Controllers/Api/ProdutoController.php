<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Produto\StoreProdutoRequest;
use App\Http\Requests\Produto\UpdateProdutoRequest;
use App\Models\Produto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Produtos",
 *     description="Gerenciamento de Produtos"
 * )
 */
class ProdutoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/produtos",
     *     summary="Listar todos os produtos",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="search", in="query", schema={"type":"string"}),
     *     @OA\Parameter(name="categoria_id", in="query", schema={"type":"integer"}),
     *     @OA\Parameter(name="em_falta", in="query", schema={"type":"boolean"}),
     *     @OA\Response(response=200, description="Lista de produtos")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Produto::with(['categoria', 'estoques.farmacia']);

        if ($request->has('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%')
                  ->orWhere('codigo', 'like', '%' . $request->search . '%');
        }

        if ($request->has('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->boolean('em_falta')) {
            $query->whereHas('estoques', function ($q) {
                $q->whereColumn('quantidade', '<', 'stock_minimo');
            });
        }

        $produtos = $query->paginate($request->get('per_page', 15));

        return response()->json($produtos);
    }

    /**
     * @OA\Post(
     *     path="/api/produtos",
     *     summary="Criar novo produto",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome","codigo","preco","categoria_id"},
     *             @OA\Property(property="nome", type="string"),
     *             @OA\Property(property="codigo", type="string"),
     *             @OA\Property(property="preco", type="number", format="float"),
     *             @OA\Property(property="data_validade", type="string", format="date"),
     *             @OA\Property(property="categoria_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Produto criado com sucesso")
     * )
     */
    public function store(StoreProdutoRequest $request): JsonResponse
    {
        $produto = Produto::create($request->validated());

        return response()->json($produto->load('categoria'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/produtos/{id}",
     *     summary="Obter detalhes de um produto",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=200, description="Detalhes do produto")
     * )
     */
    public function show(Produto $produto): JsonResponse
    {
        return response()->json($produto->load(['categoria', 'estoques.farmacia']));
    }

    /**
     * @OA\Put(
     *     path="/api/produtos/{id}",
     *     summary="Atualizar produto",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=200, description="Produto atualizado com sucesso")
     * )
     */
    public function update(UpdateProdutoRequest $request, Produto $produto): JsonResponse
    {
        $produto->update($request->validated());

        return response()->json($produto->load('categoria'));
    }

    /**
     * @OA\Delete(
     *     path="/api/produtos/{id}",
     *     summary="Deletar produto",
     *     tags={"Produtos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=204, description="Produto deletado com sucesso")
     * )
     */
    public function destroy(Produto $produto): JsonResponse
    {
        $produto->delete();

        return response()->json(null, 204);
    }
}