<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    /**
     * Testa a rota de login.
     *
     * @return void
     */
    public function test_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'dev@betalent.tech',
            'token' => 'FEC9BB078BF338F464F96B48089EB498'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);
    }

    // /**
    //  * Testa a criação de uma compra.
    //  *
    //  * @return void
    //  */
    // public function test_create_purchase()
    // {
    //     $response = $this->postJson('/api/transaction', [
    //         'amount' => 1000, // valor da compra em centavos
    //         'name' => 'Tester',
    //         'email' => 'tester@email.com',
    //         'cardNumber' => '5569000000006063',
    //         'cvv' => '010',
    //     ]);

    //     // Verifica se o status da resposta é 200 OK
    //     $response->assertStatus(200);

    //     // Verifica se a resposta JSON contém os dados esperados
    //     $response->assertJsonStructure([
    //         'transaction_id',
    //         'status',
    //     ]);
    // }
}
