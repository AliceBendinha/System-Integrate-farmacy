<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servico\StoreServicoRequest;
use App\Http\Requests\Servico\UpdateServicoRequest;
use App\Models\Servico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Servicos",
 *     description="Gerenciamento de Serviços"
 * )
 */
class ServicoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/servicos",
     *     summary="Listar todos os serviços",
     *     tags={"Servicos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="farmacia_id", in="query", schema={"type":"integer"}),
     *     @OA\Response(response=200, description="Lista de serviços")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Servico::with('farmacia');

        if ($request->has('farmacia_id')) {
            $query->where('farmacia_id', $request->farmacia_id);
        }

        $servicos = $query->paginate($request->get('per_page', 15));

        return response()->json($servicos);
    }

    /**
     * @OA\Post(
     *     path="/api/servicos",
     *     summary="Criar novo serviço",
     *     tags={"Servicos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"farmacia_id","nome","preco"},
     *             @OA\Property(property="farmacia_id", type="integer"),
     *             @OA\Property(property="nome", type="string"),
     *             @OA\Property(property="descricao", type="string"),
     *             @OA\Property(property="preco", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Serviço criado com sucesso")
     * )
     */
    public function store(StoreServicoRequest $request): JsonResponse
    {
        $servico = Servico::create($request->validated());

        return response()->json($servico->load('farmacia'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/servicos/{id}",
     *     summary="Obter detalhes de um serviço",
     *     tags={"Servicos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=200, description="Detalhes do serviço")
     * )
     */
    public function show(Servico $servico): JsonResponse
    {
        return response()->json($servico->load('farmacia'));
    }

    /**
     * @OA\Put(
     *     path="/api/servicos/{id}",
     *     summary="Atualizar serviço",
     *     tags={"Servicos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=200, description="Serviço atualizado com sucesso")
     * )
     */
    public function update(UpdateServicoRequest $request, Servico $servico): JsonResponse
    {
        $servico->update($request->validated());

        return response()->json($servico->load('farmacia'));
    }

    /**
     * @OA\Delete(
     *     path="/api/servicos/{id}",
     *     summary="Deletar serviço",
     *     tags={"Servicos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=204, description="Serviço deletado com sucesso")
     * )
     */
    public function destroy(Servico $servico): JsonResponse
    {
        $servico->delete();

        return response()->json(null, 204);
    }
}
