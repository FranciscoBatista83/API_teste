<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

// Login
Route::post('/login', [AuthController::class, 'login']);

// Realizar compra informando o produto
Route::post('/compras', [TransactionController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (JWT)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // Ativar/desativar gateway
    Route::patch('/gateways/{id}/toggle', [GatewayController::class, 'toggleStatus']);

    // Alterar prioridade do gateway
    Route::patch('/gateways/{id}/prioridade', [GatewayController::class, 'changePriority']);

    // CRUD de usuários (Apenas para Admin)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('/usuarios', UserController::class);
    });

    // CRUD de produtos (Apenas para Admin)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('/produtos', ProductController::class);
    });

    // Listar todos os clientes
    Route::get('/clientes', [ClientController::class, 'index']);

    // Detalhes do cliente e suas compras
    Route::get('/clientes/{id}', [ClientController::class, 'show']);

    // Listar todas as compras
    Route::get('/compras', [TransactionController::class, 'index']);

    // Detalhes de uma compra
    Route::get('/compras/{id}', [TransactionController::class, 'show']);

    // Reembolso de uma compra (Apenas Admin)
    Route::post('/compras/{id}/reembolso', [TransactionController::class, 'refund'])->middleware('role:admin');
});
