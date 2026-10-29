<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiProdutoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_cria_produto_com_margem_correta(): void
    {
        $this->postJson('/api/produtos', [
            'nome' => 'Lajota',
            'preco_custo' => 1000,
            'preco' => 1100,
        ])->assertCreated();

        $this->assertDatabaseHas('produtos', ['nome' => 'Lajota']);
    }
}
