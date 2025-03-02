<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    /**
     * Ativa ou desativa o gateway.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus($id)
    {
        // Encontrar o gateway pelo ID
        $gateway = Gateway::findOrFail($id);

        // Alterna o status de ativo/inativo
        $gateway->is_active = !$gateway->is_active;
        $gateway->save();

        // Retorna uma resposta de sucesso
        return response()->json([
            'message' => 'Gateway status atualizado com sucesso!',
            'data' => $gateway
        ]);
    }

    /**
     * Altera a prioridade do gateway.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePriority(Request $request, $id)
    {
        // Valida a entrada para garantir que a prioridade seja um número inteiro
        $validated = $request->validate([
            'priority' => 'required|integer|min:1'
        ]);

        // Encontrar o gateway pelo ID
        $gateway = Gateway::findOrFail($id);

        // Altera a prioridade
        $gateway->priority = $validated['priority'];
        $gateway->save();

        // Retorna uma resposta de sucesso
        return response()->json([
            'message' => 'Prioridade do gateway atualizada com sucesso!',
            'data' => $gateway
        ]);
    }
}
