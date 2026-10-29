<?php

namespace App\Services;

use App\Models\Produto;
use App\Repositories\ProdutoRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class ProdutoService
{
    private const MARGEM_MINIMA = 0.10;

    public function __construct(private ProdutoRepositoryInterface $produtos)
    {
    }

    public function listar(array $filtros): LengthAwarePaginator
    {
        return $this->produtos->search($filtros);
    }

    public function create(array $dados): Produto
    {
        $this->verificarMargem((float) $dados['preco'], (float) $dados['preco_custo']);

        return $this->produtos->create($dados);
    }

    public function update(Produto $produto, array $dados): Produto
    {
        // Se o campo não vier na requisição, usa o valor atual do produto
        $preco = (float) ($dados['preco'] ?? $produto->preco);
        $custo = (float) ($dados['preco_custo'] ?? $produto->preco_custo);

        $this->verificarMargem($preco, $custo);

        return $this->produtos->update($produto, $dados);
    }

    public function delete(Produto $produto): void
    {
        $this->produtos->delete($produto);
    }

    private function verificarMargem(float $preco, float $preco_custo): void
    {
        // O preço mínimo deve ser o custo + 10%
        $margem = $preco_custo + ($preco_custo * self::MARGEM_MINIMA);
        $minimo = round($margem, 2);

        if ($preco < $minimo) {
            throw ValidationException::withMessages([
                'preco' => sprintf('O preço do produto deve ser no mínimo %.2f (Margem Mínima)', $minimo),
            ]);
        }
    }
}