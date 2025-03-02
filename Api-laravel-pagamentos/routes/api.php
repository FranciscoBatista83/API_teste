<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TransactionProductController;

/*
|---------------------------------------------------------------------------
| Rotas Públicas
|---------------------------------------------------------------------------
*/

// ** Tabela: Auth (Login) **
Route::post('/login', [AuthController::class, 'login']); // Realiza login

// ** Tabela: Transações (Compras) **
Route::post('/compras', [TransactionController::class, 'store']); // Realiza compra informando o produto

/*
|---------------------------------------------------------------------------
| Rotas Protegidas (JWT)
|---------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // ** Tabela: Gateways **
    // Ativar/desativar gateway
    Route::patch('/gateways/{id}/toggle', [GatewayController::class, 'toggleStatus']);
    // Alterar prioridade do gateway
    Route::patch('/gateways/{id}/prioridade', [GatewayController::class, 'changePriority']);


    // ** Tabela: Usuários (Admin) **
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('/usuarios', UserController::class); // CRUD de usuários
    });

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('/usuarios', UserController::class);
    });
    

    // ** Tabela: Produtos (Admin) **
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('/produtos', ProductController::class); // CRUD de produtos

    // Listar todos os produtos
    Route::get('/produtos', [ProductController::class, 'index']);
    // Exibir um produto específico
    Route::get('/produtos/{id}', [ProductController::class, 'show']);
    // Criar um novo produto
    Route::post('/produtos', [ProductController::class, 'store']);
    // Atualizar um produto existente
    Route::put('/produtos/{id}', [ProductController::class, 'update']);
    // Deletar um produto
    Route::delete('/produtos/{id}', [ProductController::class, 'destroy']);
    });

    // ** Tabela: Clientes **
    Route::get('/clientes', [ClientController::class, 'index']); // Lista todos os clientes
    Route::post('/clientes', [ClientController::class, 'store']); // Cria um novo cliente
    Route::get('/clientes/{id}', [ClientController::class, 'show']); // Exibe um cliente pelo ID
    Route::put('/clientes/{id}', [ClientController::class, 'update']); // Atualiza um cliente
    Route::delete('/clientes/{id}', [ClientController::class, 'destroy']); // Deleta um cliente

    // ** Tabela: Transações (Compras) **
    Route::get('/compras', [TransactionController::class, 'index']); // Lista todas as compras
    Route::get('/compras/{id}', [TransactionController::class, 'show']); // Detalha uma compra
    Route::post('/compras/{id}/reembolso', [TransactionController::class, 'refund'])->middleware('role:admin'); // Reembolso (Apenas Admin)
    // Registrar uma nova transação
    Route::post('/compras', [TransactionController::class, 'store']);

    // Listar todos os produtos de uma transação
    Route::get('/transacoes/{transactionId}/produtos', [TransactionProductController::class, 'index']);

    // Adicionar um produto a uma transação
    Route::post('/transacoes/{transactionId}/produtos', [TransactionProductController::class, 'store']);

    // Atualizar a quantidade de um produto em uma transação
    Route::put('/transacoes/{transactionId}/produtos/{productId}', [TransactionProductController::class, 'update']);

    // Remover um produto de uma transação
    Route::delete('/transacoes/{transactionId}/produtos/{productId}', [TransactionProductController::class, 'destroy']);

});
