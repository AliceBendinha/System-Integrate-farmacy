<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesquisa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Pesquisas",
 *     description="Histórico de pesquisas dos usuários"
 * )
 */
class PesquisaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pesquisas",
     *     summary="Listar pesquisas do usuário autenticado",
     *     tags={"Pesquisas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de pesquisas")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $pesquisas = Pesquisa::where('usuario_id', auth('api')->id())
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($pesquisas);
    }

    /**
     * @OA\Post(
     *     path="/api/pesquisas",
     *     summary="Registrar nova pesquisa",
     *     tags={"Pesquisas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"termo","tipo_pesquisa"},
     *             @OA\Property(property="termo", type="string"),
     *             @OA\Property(property="tipo_pesquisa", type="string", enum={"produto","farmacia","servico"}),
     *             @OA\Property(property="resultados_encontrados", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Pesquisa registrada")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'termo' => 'required|string|max:255',
            'tipo_pesquisa' => 'required|in:produto,farmacia,servico',
            'resultados_encontrados' => 'nullable|integer|min:0',
        ]);

        $pesquisa = Pesquisa::create([
            'usuario_id' => auth('api')->id(),
            'termo' => $request->termo,
            'tipo_pesquisa' => $request->tipo_pesquisa,
            'resultados_encontrados' => $request->resultados_encontrados ?? 0,
        ]);

        return response()->json($pesquisa, 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/pesquisas/{id}",
     *     summary="Deletar pesquisa",
     *     tags={"Pesquisas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=204, description="Pesquisa deletada")
     * )
     */
    public function destroy(Pesquisa $pesquisa): JsonResponse
    {
        // Verificar se é do usuário autenticado
        if ($pesquisa->usuario_id !== auth('api')->id() && !auth('api')->user()?->is_admin) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $pesquisa->delete();

        return response()->json(null, 204);
    }
}
