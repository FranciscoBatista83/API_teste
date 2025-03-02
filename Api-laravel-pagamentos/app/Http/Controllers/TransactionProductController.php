<?php

namespace App\Http\Controllers;

use App\Models\TransactionProduct;
use Illuminate\Http\Request;

class TransactionProductController extends Controller
{
    /**
     * Lista os produtos de uma transação específica.
     *
     * @param  int  $transactionId
     * @return \Illuminate\Http\Response
     */
    public function index($transactionId)
    {
        // Encontra os produtos associados à transação
        $transactionProducts = TransactionProduct::where('transaction_id', $transactionId)->get();

        return response()->json([
            'data' => $transactionProducts
        ]);
    }

    /**
     * Adiciona um produto a uma transação.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $transactionId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $transactionId)
    {
        // Valida os dados de entrada
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Adiciona o produto à transação
        $transactionProduct = TransactionProduct::create([
            'transaction_id' => $transactionId,
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
        ]);

        return response()->json([
            'message' => 'Produto adicionado à transação com sucesso!',
            'data' => $transactionProduct
        ], 201);
    }

    /**
     * Atualiza a quantidade de um produto em uma transação.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $transactionId
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $transactionId, $productId)
    {
        // Valida os dados de entrada
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Encontra o produto da transação
        $transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
                                                 ->where('product_id', $productId)
                                                 ->firstOrFail();

        // Atualiza a quantidade
        $transactionProduct->quantity = $validated['quantity'];
        $transactionProduct->save();

        return response()->json([
            'message' => 'Quantidade do produto atualizada com sucesso!',
            'data' => $transactionProduct
        ]);
    }

    /**
     * Remove um produto de uma transação.
     *
     * @param  int  $transactionId
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function destroy($transactionId, $productId)
    {
        // Encontra o produto da transação
        $transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
                                                 ->where('product_id', $productId)
                                                 ->firstOrFail();

        // Remove o produto da transação
        $transactionProduct->delete();

        return response()->json([
            'message' => 'Produto removido da transação com sucesso!'
        ]);
    }
}
