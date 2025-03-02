<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Lista todas as transações.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retorna todas as transações, com relacionamento com cliente e gateway
        $transactions = Transaction::with(['client', 'gateway'])->get();

        return response()->json([
            'data' => $transactions
        ]);
    }

    /**
     * Exibe uma transação específica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Encontra a transação pelo ID e carrega as relações
        $transaction = Transaction::with(['client', 'gateway', 'products'])->findOrFail($id);

        return response()->json([
            'data' => $transaction
        ]);
    }

    /**
     * Registra uma nova transação.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validação dos dados enviados
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'gateway_id' => 'required|exists:gateways,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,failed',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1'
        ]);

        // Inicia uma transação de banco de dados para garantir que tudo seja salvo ou revertido
        DB::beginTransaction();

        try {
            // Cria a transação principal
            $transaction = Transaction::create([
                'client_id' => $validated['client_id'],
                'gateway_id' => $validated['gateway_id'],
                'amount' => $validated['amount'],
                'status' => $validated['status'],
                'card_last_numbers' => $request->card_last_numbers,
            ]);

            // Associa os produtos à transação
            foreach ($validated['products'] as $product) {
                TransactionProduct::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                ]);
            }

            // Se tudo ocorreu bem, confirma a transação
            DB::commit();

            return response()->json([
                'message' => 'Transação criada com sucesso!',
                'data' => $transaction
            ], 201);

        } catch (\Exception $e) {
            // Caso ocorra algum erro, desfaz as mudanças no banco
            DB::rollBack();

            return response()->json([
                'message' => 'Erro ao criar transação',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Solicita reembolso de uma transação.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function refund($id)
    {
        // Encontra a transação pelo ID
        $transaction = Transaction::findOrFail($id);

        // Verifica se a transação pode ser reembolsada
        if ($transaction->status != 'completed') {
            return response()->json([
                'message' => 'A transação não está completa, portanto não pode ser reembolsada.'
            ], 400);
        }

        // Atualiza o status para "reembolsado"
        $transaction->status = 'refunded';
        $transaction->save();

        return response()->json([
            'message' => 'Transação reembolsada com sucesso!',
            'data' => $transaction
        ]);
    }
}
