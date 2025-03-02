<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Exibe uma lista de todos os recursos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retorna todos os usuários
        return User::all();
    }

    /**
     * Cria um novo recurso e o armazena.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validação dos dados enviados
        $request->validate([
            'email' => 'required|email|unique:users', // O e-mail é obrigatório, deve ser único e válido
            'password' => 'required|min:6', // A senha é obrigatória e deve ter no mínimo 6 caracteres
            'role' => 'required|in:admin,client', // O papel (role) deve ser admin ou client
        ]);

        // Criação do usuário
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password), // A senha é criptografada
            'role' => $request->role,
        ]);

        // Retorna o usuário criado com status 201 (Criado)
        return response()->json($user, 201);
    }

    /**
     * Exibe o recurso especificado.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // Exibe os dados do usuário especificado
        return response()->json($user);
    }

    /**
     * Atualiza o recurso especificado no armazenamento.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Validação dos dados enviados
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id, // O e-mail deve ser único, exceto o e-mail do usuário atual
            'password' => 'nullable|min:6', // A senha é opcional, mas se fornecida deve ter no mínimo 6 caracteres
            'role' => 'required|in:admin,client', // O papel (role) deve ser admin ou client
        ]);

        // Atualizando os dados do usuário
        $user->update([
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password, // Se a senha foi fornecida, ela é criptografada
            'role' => $request->role,
        ]);

        // Retorna os dados do usuário atualizado
        return response()->json($user);
    }

    /**
     * Remove o recurso especificado do armazenamento.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Deleta o usuário
        $user->delete();

        // Retorna resposta vazia com status 204 (Sem Conteúdo), indicando que a exclusão foi bem-sucedida
        return response()->json(null, 204);
    }
}
