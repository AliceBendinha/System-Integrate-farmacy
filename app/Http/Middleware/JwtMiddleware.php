<?php

namespace App\Http\Middleware;

use App\Support\Jwt\JwtService;
use Closure;
use Illuminate\Http\Request;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Obter header Authorization
        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Token não fornecido'
            ], 401);
        }

        // Extrair token do formato "Bearer <token>"
        if (!str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Formato de token inválido. Use: Bearer <token>'
            ], 401);
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {
            // Validar e decodificar token
            $jwtService = app(JwtService::class);
            $payload = $jwtService->validarToken($token);

            // Buscar usuário do banco de dados
            $user = \App\Models\User::find($payload->sub);

            if (!$user) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'Usuário não encontrado'
                ], 401);
            }

            // Injetar usuário na request para acesso em controllers
            auth('api')->setUser($user);

            // Avisar se token está próximo de expirar
            if ($jwtService->estaProximoDeExpirar($token, minutos: 5)) {
                $request->attributes->add(['token_expiring_soon' => true]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}

