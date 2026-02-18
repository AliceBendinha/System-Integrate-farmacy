<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FarmaciaController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\EstoqueController;
use App\Http\Controllers\Api\ServicoController;
use App\Http\Controllers\Api\PesquisaController;
use App\Http\Controllers\Api\RelatorioController;

/**
 * @OA\Info(
 *     title="Farmacia API",
 *     version="1.0.0",
 *     description="API REST para gerenciamento de farmácias, produtos e estoques",
 * )
 * 
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token based authentication",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */

// ===== AUTENTICAÇÃO (Público) =====
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout')->middleware('jwt');
Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('refresh')->middleware('jwt');

// ===== ROTAS PROTEGIDAS COM JWT =====
Route::middleware('jwt')->group(function () {
    
    // ===== FARMACIAS =====
    Route::apiResource('farmacias', FarmaciaController::class);
    
    // ===== PRODUTOS =====
    Route::apiResource('produtos', ProdutoController::class);
    
    // ===== ESTOQUES =====
    Route::apiResource('estoques', EstoqueController::class);
    Route::post('/estoques/{estoque}/repor', [EstoqueController::class, 'repor'])->name('estoques.repor');
    Route::post('/estoques/{estoque}/remover', [EstoqueController::class, 'remover'])->name('estoques.remover');
    
    // ===== SERVIÇOS =====
    Route::apiResource('servicos', ServicoController::class);
    
    // ===== PESQUISAS =====
    Route::apiResource('pesquisas', PesquisaController::class, ['only' => ['index', 'store', 'destroy']]);
    
    // ===== RELATÓRIOS =====
    Route::prefix('relatorios')->group(function () {
        Route::get('/estoques-em-falta', [RelatorioController::class, 'estoquesEmFalta']);
        Route::get('/produtos-vencidos', [RelatorioController::class, 'produtosVencidos']);
        Route::get('/resumo', [RelatorioController::class, 'resumo']);
        Route::get('/estoque-por-farmacia', [RelatorioController::class, 'estoquePorFarmacia']);
        Route::get('/movimentacao', [RelatorioController::class, 'movimentacao']);
    });
    
});

