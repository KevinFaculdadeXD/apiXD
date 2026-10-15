<?php

namespace App\Services;

use Illuminate\Validation\ValidationException as LaravelValidationException;

class ProdutoService
{
    private const MARGEM_MINIMA = 0.10;

    public function create(array $dados)
    {
        $this->verificarMargem($dados['preco'], $dados['preco_custo']);

        $produto =

        return Produto::create($dados);
    }
    private function verificarMargem(float $preco,
    float $preco_custo)
    {
        // O preço minimo do produto deve ser 
        // maior ou igual a 10% do valor de custo
        $margem = $preco_custo + ($preco_custo*self::MARGEM_MINIMA);
        $minimo = round($margem, 2);

        if($preco < $minimo){
            throw ValidationException::withMessages([
                'preco' => sprintf('O preco do protudo 
                deve ser no minimo $.2f (Margem Mínima)', $minimo),
            ]);
        }
    }
}