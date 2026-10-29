<?php

namespace App\Repositories;

use App\Models\Produto;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentProdutoRepository implements ProdutoRepositoryInterface
{
    public function search(array $filtros = [], int $porPagina = 10): LengthAwarePaginator
    {
        return Produto::query()
            // Filtro original: busca por nome
            ->when(
                $filtros['search'] ?? null,
                fn ($query, $busca) => $query->where('nome', 'like', '%' . $busca . '%')
            )
            // Novo filtro 1: faixa de preço
            ->when(
                $filtros['preco_min'] ?? null,
                fn ($query, $min) => $query->where('preco', '>=', $min)
            )
            ->when(
                $filtros['preco_max'] ?? null,
                fn ($query, $max) => $query->where('preco', '<=', $max)
            )
            // Novo filtro 2: disponibilidade em estoque
            ->when(
                array_key_exists('em_estoque', $filtros) && $filtros['em_estoque'] !== null,
                fn ($query) => $filtros['em_estoque']
                    ? $query->where('estoque', '>', 0)
                    : $query->where('estoque', '<=', 0)
            )
            ->orderBy('nome')
            ->paginate($porPagina);
    }

    public function create(array $dados): Produto
    {
        return Produto::create($dados);
    }

    public function update(Produto $produto, array $dados): Produto
    {
        $produto->update($dados);

        return $produto->refresh();
    }

    public function delete(Produto $produto): void
    {
        $produto->delete();
    }
}