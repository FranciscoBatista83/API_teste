<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Lista todos os produtos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retorna todos os produtos
        $products = Product::all();

        return response()->json([
            'data' => $products
        ]);
    }

    /**
     * Exibe um produto específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Encontra o produto pelo ID
        $product = Product::findOrFail($id);

        return response()->json([
            'data' => $product
        ]);
    }

    /**
     * Cria um novo produto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valida os dados de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0'
        ]);

        // Cria o produto
        $product = Product::create($validated);

        return response()->json([
            'message' => 'Produto criado com sucesso!',
            'data' => $product
        ], 201);
    }

    /**
     * Atualiza um produto existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Valida os dados de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0'
        ]);

        // Encontra o produto pelo ID
        $product = Product::findOrFail($id);

        // Atualiza os dados do produto
        $product->update($validated);

        return response()->json([
            'message' => 'Produto atualizado com sucesso!',
            'data' => $product
        ]);
    }

    /**
     * Remove um produto existente.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Encontra o produto pelo ID
        $product = Product::findOrFail($id);

        // Remove o produto
        $product->delete();

        return response()->json([
            'message' => 'Produto deletado com sucesso!'
        ]);
    }
}
