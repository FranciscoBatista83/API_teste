<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Retorna todos os clientes
    public function index()
    {
        return Client::all(); // Retorna todos os registros da tabela clients
    }

    // Cria um novo cliente
    public function store(Request $request)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
        ]);

        // Criação do cliente
        $client = Client::create($validatedData);

        // Retorna o cliente criado com status 201 (created)
        return response()->json($client, 201);
    }

    // Retorna um cliente específico
    public function show($id)
    {
        // Busca o cliente pelo ID
        $client = Client::findOrFail($id); // Retorna erro 404 caso não encontre

        // Retorna os dados do cliente
        return response()->json($client);
    }

    // Atualiza um cliente
    public function update(Request $request, $id)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $id,
        ]);

        // Busca o cliente pelo ID
        $client = Client::findOrFail($id);

        // Atualiza os dados do cliente
        $client->update($validatedData);

        // Retorna o cliente atualizado
        return response()->json($client);
    }

    // Exclui um cliente
    public function destroy($id)
    {
        // Busca o cliente pelo ID
        $client = Client::findOrFail($id);

        // Exclui o cliente
        $client->delete();

        // Retorna uma resposta de sucesso
        return response()->json(null, 204); // 204 = No Content
    }
}
