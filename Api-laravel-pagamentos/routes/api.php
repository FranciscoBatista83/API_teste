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
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
|
| Estas rotas podem ser acessadas por qualquer usuário, sem necessidade de
| autenticação. Aqui incluímos a rota de login e a rota para realizar compras.
|
*/

// ** Tabela: Auth (Login) **
Route::post('/login', [AuthController::class, 'login']); // Realiza login

// ** Tabela: Transações (Compras) **
Route::post('/compras', [TransactionController::class, 'store']); // Realiza compra informando o produto


/*
|--------------------------------------------------------------------------
| Rotas Protegidas (JWT)
|--------------------------------------------------------------------------
|
| Estas rotas requerem autenticação e são acessíveis apenas por usuários
| autenticados via token. O middleware 'auth:sanctum' é utilizado para isso.
|
*/

// Route::middleware('auth:sanctum')->group(function () {
Route::group([], function () {

    // ** Tabela: Gateways **
    // Ativar ou desativar um gateway
    Route::patch('/gateways/{id}/toggle', [GatewayController::class, 'toggleStatus']);
    // Alterar a prioridade de um gateway
    Route::patch('/gateways/{id}/prioridade', [GatewayController::class, 'changePriority']);

    /*
    |--------------------------------------------------------------------------
    | Rotas Administrativas (somente para usuários com papel de 'admin')
    |--------------------------------------------------------------------------
    |
    | Essas rotas são protegidas por um middleware que verifica se o usuário tem
    | a permissão de administrador. Apenas usuários com essa função podem acessar.
    |
    */

    Route::middleware('role:admin')->group(function () {

        // ** Tabela: Usuários (Admin) **
        Route::apiResource('/usuarios', UserController::class); // CRUD de usuários

        // ** Tabela: Produtos (Admin) **
        Route::apiResource('/produtos', ProductController::class); // CRUD de produtos
    });

    Route::get('/teste', function () {
        return response()->json(['message' => 'API está funcionando!']);
    });

    // ** Tabela: Produtos (Listagem pública) **
    Route::get('/produtos', [ProductController::class, 'index']); // Lista todos os produtos
    Route::get('/produtos/{id}', [ProductController::class, 'show']); // Exibe um produto específico
    Route::post('/produtos', [ProductController::class, 'store']); // Cria novo produto

    // ** Tabela: Clientes **
    Route::get('/clientes', [ClientController::class, 'index']); // Lista todos os clientes
    Route::post('/clientes', [ClientController::class, 'store']); // Cria um novo cliente
    Route::get('/clientes/{id}', [ClientController::class, 'show']); // Exibe um cliente pelo ID
    Route::put('/clientes/{id}', [ClientController::class, 'update']); // Atualiza um cliente
    Route::delete('/clientes/{id}', [ClientController::class, 'destroy']); // Deleta um cliente

    // ** Tabela: Transações (Compras) **
    Route::get('/compras', [TransactionController::class, 'index']); // Lista todas as compras
    Route::get('/compras/{id}', [TransactionController::class, 'show']); // Detalha uma compra
    Route::post('/compras/{id}/reembolso', [TransactionController::class, 'refund'])->middleware('role:admin'); // Reembolso (somente Admin)
    Route::post('/compras', [TransactionController::class, 'store']); // Registrar uma nova transação

    // ** Tabela: Transações (Produtos dentro de uma transação) **
    Route::get('/transacoes/{transactionId}/produtos', [TransactionProductController::class, 'index']); // Lista todos os produtos de uma transação
    Route::post('/transacoes/{transactionId}/produtos', [TransactionProductController::class, 'store']); // Adiciona um produto a uma transação
    Route::put('/transacoes/{transactionId}/produtos/{productId}', [TransactionProductController::class, 'update']); // Atualiza a quantidade de um produto em uma transação
    Route::delete('/transacoes/{transactionId}/produtos/{productId}', [TransactionProductController::class, 'destroy']); // Remove um produto de uma transação

});
