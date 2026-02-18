<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\Jwt\JwtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Autenticação",
 *     description="Endpoints de autenticação e autorização"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Fazer login",
     *     description="Autentica usuário e retorna JWT token",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Credenciais inválidas"),
     *     @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function login(LoginRequest $request, JwtService $jwt): JsonResponse
    {
        try {
            $token = $jwt->gerarToken(
                $request->email,
                $request->password
            );

            return response()->json([
                'token' => $token,
                'type'  => 'bearer',
                'expires_in' => config('jwt.ttl')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Credenciais inválidas',
                'message' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Fazer logout",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/auth/refresh",
     *     summary="Renovar token JWT",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token renovado",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Token inválido")
     * )
     */
    public function refresh(Request $request, JwtService $jwt): JsonResponse
    {
        try {
            $authHeader = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $authHeader);

            // Valida token atual
            $payload = $jwt->validarToken($token);

            // Gera novo token
            $user = \App\Models\User::find($payload->sub);

            if (!$user) {
                return response()->json(['error' => 'Usuário não encontrado'], 404);
            }

            $newToken = $jwt->gerarToken($user->email, null, forceNew: true);

            return response()->json([
                'token' => $newToken,
                'type'  => 'bearer',
                'expires_in' => config('jwt.ttl')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao renovar token',
                'message' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * @OA\Get(
     *     path="/auth/me",
     *     summary="Obter dados do usuário autenticado",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dados do usuário",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function me(Request $request): JsonResponse
    {
        $user = auth('api')->user();
        if ($user instanceof \Illuminate\Database\Eloquent\Model) {
            $user->load('role', 'farmacias');
        }
        return response()->json($user);
    }
}

