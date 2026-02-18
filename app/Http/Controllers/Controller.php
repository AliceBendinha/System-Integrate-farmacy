<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Farmacia API",
 *    version="1.0.0",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Resposta de sucesso padronizada
     */
    protected function successResponse($data, string $message = 'Sucesso', int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Resposta de erro padronizada
     */
    protected function errorResponse(string $message, $data = null, int $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Resposta de erro não autenticado
     */
    protected function unauthorizedResponse(string $message = 'Não autenticado')
    {
        return $this->errorResponse($message, null, 401);
    }

    /**
     * Resposta de erro não autorizado
     */
    protected function forbiddenResponse(string $message = 'Não autorizado')
    {
        return $this->errorResponse($message, null, 403);
    }

    /**
     * Resposta não encontrado
     */
    protected function notFoundResponse(string $message = 'Recurso não encontrado')
    {
        return $this->errorResponse($message, null, 404);
    }

    /**
     * Resposta de erro validação
     */
    protected function validationErrorResponse($errors, string $message = 'Erro de validação')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], 422);
    }
}
