<?php

namespace App\Support\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     openapi="3.0.0",
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Farmacia API - REST + JWT + PostgreSQL",
 *         description="API REST completa para gerenciamento de farmácias, produtos, estoques e serviços",
 *         @OA\Contact(
 *             name="Suporte",
 *             url="https://api.farmacia.local"
 *         )
 *     ),
 *     @OA\Server(
 *         url="http://localhost:8000/api",
 *         description="Servidor Local"
 *     ),
 *     @OA\Server(
 *         url="https://api.farmacia.com",
 *         description="Servidor Produção"
 *     ),
 *     @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *         bearerFormat="JWT",
 *         description="JWT Token obtido no login"
 *     )
 * )
 */

class OpenApiConfig
{
    /**
     * ========================================
     * SCHEMAS - Definições de estruturas
     * ========================================
     */

    /**
     * @OA\Schema(
     *     schema="User",
     *     title="Usuário",
     *     description="Modelo de usuário da aplicação",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="João Silva"),
     *     @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *     @OA\Property(property="role_id", type="integer", example=1),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     */

    /**
     * @OA\Schema(
     *     schema="Farmacia",
     *     title="Farmácia",
     *     description="Modelo de farmácia",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="nome", type="string", example="Farmacia Central"),
     *     @OA\Property(property="localizacao", type="string", example="Rua Principal, 123"),
     *     @OA\Property(property="descricao", type="string"),
     *     @OA\Property(property="telefone", type="string"),
     *     @OA\Property(property="email", type="string", format="email"),
     *     @OA\Property(property="user_id", type="integer"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     */

    /**
     * @OA\Schema(
     *     schema="Produto",
     *     title="Produto",
     *     description="Modelo de produto farmacêutico",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="nome", type="string", example="Dipirona 500mg"),
     *     @OA\Property(property="codigo", type="string", example="DIP-500-001", unique=true),
     *     @OA\Property(property="descricao", type="string"),
     *     @OA\Property(property="preco", type="number", format="float", example=10.50),
     *     @OA\Property(property="data_validade", type="string", format="date"),
     *     @OA\Property(property="categoria_id", type="integer"),
     *     @OA\Property(property="fabricante", type="string"),
     *     @OA\Property(property="lote", type="string"),
     *     @OA\Property(property="em_falta", type="boolean"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     */

    /**
     * @OA\Schema(
     *     schema="Estoque",
     *     title="Estoque",
     *     description="Registro de estoque em farmácia",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="farmacia_id", type="integer", example=1),
     *     @OA\Property(property="produto_id", type="integer", example=1),
     *     @OA\Property(property="quantidade", type="integer", example=100),
     *     @OA\Property(property="stock_minimo", type="integer", example=10),
     *     @OA\Property(property="em_falta", type="boolean"),
     *     @OA\Property(property="percentual_estoque", type="number", format="float"),
     *     @OA\Property(property="ultima_atualizacao", type="string", format="date-time"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     */

    /**
     * @OA\Schema(
     *     schema="Servico",
     *     title="Serviço",
     *     description="Serviço oferecido pela farmácia",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="farmacia_id", type="integer", example=1),
     *     @OA\Property(property="nome", type="string", example="Teste de Pressão"),
     *     @OA\Property(property="descricao", type="string"),
     *     @OA\Property(property="preco", type="number", format="float", example=15.00),
     *     @OA\Property(property="ativo", type="boolean"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     */

    /**
     * @OA\Schema(
     *     schema="AuthResponse",
     *     title="Resposta de Autenticação",
     *     description="Resposta após login bem-sucedido",
     *     @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."),
     *     @OA\Property(property="type", type="string", example="bearer"),
     *     @OA\Property(property="expires_in", type="integer", example=3600)
     * )
     */

    /**
     * @OA\Schema(
     *     schema="ErrorResponse",
     *     title="Erro",
     *     description="Resposta de erro padrão",
     *     @OA\Property(property="error", type="string", example="Mensagem de erro"),
     *     @OA\Property(property="message", type="string"),
     *     @OA\Property(property="status", type="integer", example=400)
     * )
     */

    /**
     * @OA\Schema(
     *     schema="ValidationErrorResponse",
     *     title="Erro de Validação",
     *     description="Resposta com erros de validação",
     *     @OA\Property(
     *         property="errors",
     *         type="object",
     *         additionalProperties={@OA\Property(type="array", items={@OA\Property(type="string")})}
     *     ),
     *     @OA\Property(property="message", type="string")
     * )
     */

    /**
     * @OA\Schema(
     *     schema="PaginatedResponse",
     *     title="Resposta Paginada",
     *     description="Resposta com paginação",
     *     @OA\Property(property="data", type="array", items={@OA\Items()}),
     *     @OA\Property(
     *         property="meta",
     *         type="object",
     *         @OA\Property(property="current_page", type="integer"),
     *         @OA\Property(property="from", type="integer"),
     *         @OA\Property(property="last_page", type="integer"),
     *         @OA\Property(property="per_page", type="integer"),
     *         @OA\Property(property="to", type="integer"),
     *         @OA\Property(property="total", type="integer")
     *     )
     * )
     */

    /**
     * ========================================
     * PATHS - Endpoints de Autenticação
     * ========================================
     */

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Fazer login",
     *     description="Autentica usuário e retorna JWT token",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Credenciais do usuário",
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@farmacia.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     */

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Fazer logout",
     *     description="Invalida o JWT token atual",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout realizado com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou expirado",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */

    /**
     * @OA\Post(
     *     path="/auth/refresh",
     *     summary="Renovar token",
     *     description="Renova o JWT token",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token renovado",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
}
