<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Realiza o login e gera um token de autenticação.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Valida os dados recebidos na requisição
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Tenta autenticar o usuário com as credenciais fornecidas
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('Token de Acesso')->plainTextToken;

            // Retorna o token de autenticação
            return response()->json([
                'message' => 'Login realizado com sucesso!',
                'token' => $token,
            ]);
        }

        // Se as credenciais não forem válidas
        return response()->json([
            'message' => 'Credenciais inválidas!',
        ], 401);
    }
}
