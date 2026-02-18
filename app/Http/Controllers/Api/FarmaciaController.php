<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Farmacia\StoreFarmaciaRequest;
use App\Http\Requests\Farmacia\UpdateFarmaciaRequest;
use App\Models\Farmacia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Farmacias",
 *     description="Gerenciamento de Farmacias"
 * )
 */
class FarmaciaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/farmacias",
     *     summary="Listar todas as farmacias",
     *     tags={"Farmacias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de farmacias",
     *         @OA\JsonContent(type="object", properties={
     *             @OA\Property(property="data", type="array", items={@OA\Items(type="object")}),
     *             @OA\Property(property="meta", type="object")
     *         })
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Farmacia::with(['usuario', 'estoques.produto']);

        if ($request->has('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%')
                  ->orWhere('localizacao', 'like', '%' . $request->search . '%');
        }

        if (!auth('api')->user()?->is_admin) {
            $query->where('user_id', auth('api')->id());
        }

        $farmacias = $query->paginate($request->get('per_page', 15));

        return response()->json($farmacias);
    }

    /**
     * @OA\Post(
     *     path="/api/farmacias",
     *     summary="Criar nova farmacia",
     *     tags={"Farmacias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome","localizacao"},
     *             @OA\Property(property="nome", type="string", example="Farmacia Central"),
     *             @OA\Property(property="localizacao", type="string", example="Rua Principal, 123")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Farmacia criada com sucesso")
     * )
     */
    public function store(StoreFarmaciaRequest $request): JsonResponse
    {
        $farmacia = Farmacia::create([
            'nome' => $request->nome,
            'localizacao' => $request->localizacao,
            'user_id' => auth('api')->id(),
        ]);

        return response()->json($farmacia->load('usuario'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/farmacias/{id}",
     *     summary="Obter detalhes de uma farmacia",
     *     tags={"Farmacias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=200, description="Detalhes da farmacia")
     * )
     */
    public function show(Farmacia $farmacia): JsonResponse
    {
        $this->authorize('view', $farmacia);

        return response()->json($farmacia->load(['usuario', 'estoques.produto', 'servicos', 'localizacoes']));
    }

    /**
     * @OA\Put(
     *     path="/api/farmacias/{id}",
     *     summary="Atualizar farmacia",
     *     tags={"Farmacias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=200, description="Farmacia atualizada com sucesso")
     * )
     */
    public function update(UpdateFarmaciaRequest $request, Farmacia $farmacia): JsonResponse
    {
        $this->authorize('update', $farmacia);

        $farmacia->update($request->validated());

        return response()->json($farmacia->load('usuario'));
    }

    /**
     * @OA\Delete(
     *     path="/api/farmacias/{id}",
     *     summary="Deletar farmacia",
     *     tags={"Farmacias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, schema={"type":"integer"}),
     *     @OA\Response(response=204, description="Farmacia deletada com sucesso")
     * )
     */
    public function destroy(Farmacia $farmacia): JsonResponse
    {
        $this->authorize('delete', $farmacia);

        $farmacia->delete();

        return response()->json(null, 204);
    }
}