<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Estoque\StoreEstoqueRequest;
use App\Http\Requests\Estoque\UpdateEstoqueRequest;
use App\Models\Estoque;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Estoques",
 *     description="Gerenciamento de Estoques"
 * )
 */
class EstoqueController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/estoques",
     *     summary="Listar todos os estoques",
     *     tags={"Estoques"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="farmacia_id", in="query", schema={"type":"integer"}),
     *     @OA\Parameter(name="em_falta", in="query", schema={"type":"boolean"}),
     *     @OA\Response(response=200, description="Lista de estoques")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Estoque::with(['farmacia', 'produto']);

        if ($request->has('farmacia_id')) {
            $query->where('farmacia_id', $request->farmacia_id);
        }

        if ($request->boolean('em_falta')) {
            $query->whereRaw('quantidade < stock_minimo');
        }

        $estoques = $query->paginate($request->get('per_page', 15));

        return response()->json($estoques);
    }

    /**
     * @OA\Post(
     *     path="/api/estoques",
     *     summary="Criar novo registro de estoque",
     *     tags={"Estoques"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"farmacia_id","produto_id","quantidade","stock_minimo"},
     *             @OA\Property(property="farmacia_id", type="integer"),
     *             @OA\Property(property="produto_id", type="integer"),
     *             @OA\Property(property="quantidade", type="integer"),
     *             @OA\Property(property="stock_minimo", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Estoque criado com sucesso")
     * )
     */
    public function store(StoreEstoqueRequest $request): JsonResponse
    {
        $estoque = Estoque::create($request->validated());

        return response()->json($estoque->load(['farmacia', 'produto']), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/estoques/{id}",
     *     summary="Obter detalhes de um estoque",
     *     tags={"Estoques"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=200, description="Detalhes do estoque")
     * )
     */
    public function show(Estoque $estoque): JsonResponse
    {
        return response()->json($estoque->load(['farmacia', 'produto']));
    }

    /**
     * @OA\Put(
     *     path="/api/estoques/{id}",
     *     summary="Atualizar estoque",
     *     tags={"Estoques"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=200, description="Estoque atualizado com sucesso")
     * )
     */
    public function update(UpdateEstoqueRequest $request, Estoque $estoque): JsonResponse
    {
        $estoque->update($request->validated());

        return response()->json($estoque->load(['farmacia', 'produto']));
    }

    /**
     * @OA\Delete(
     *     path="/api/estoques/{id}",
     *     summary="Deletar estoque",
     *     tags={"Estoques"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=204, description="Estoque deletado com sucesso")
     * )
     */
    public function destroy(Estoque $estoque): JsonResponse
    {
        $estoque->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Post(
     *     path="/api/estoques/{id}/repor",
     *     summary="Repor estoque",
     *     tags={"Estoques"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"quantidade"},
     *             @OA\Property(property="quantidade", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Estoque reposto com sucesso")
     * )
     */
    public function repor(Request $request, Estoque $estoque): JsonResponse
    {
        $request->validate(['quantidade' => 'required|integer|min:1']);

        $estoque->repor($request->quantidade);

        return response()->json($estoque->load(['farmacia', 'produto']));
    }

    /**
     * @OA\Post(
     *     path="/api/estoques/{id}/remover",
     *     summary="Remover do estoque",
     *     tags={"Estoques"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"quantidade"},
     *             @OA\Property(property="quantidade", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Removido do estoque com sucesso")
     * )
     */
    public function remover(Request $request, Estoque $estoque): JsonResponse
    {
        $request->validate(['quantidade' => 'required|integer|min:1']);

        if (!$estoque->remover($request->quantidade)) {
            return response()->json(['error' => 'Quantidade insuficiente'], 422);
        }

        return response()->json($estoque->load(['farmacia', 'produto']));
    }
}
