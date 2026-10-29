<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutoRequest;
use App\Http\Resources\ProdutoResource;
use App\Models\Produto;
use App\Services\ProdutoService;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function __construct(private ProdutoService $service)
    {
    }

    public function index(Request $request)
    {
        $validados = $request->validate([
            'search'     => 'sometimes|nullable|string|max:255',
            'preco_min'  => 'sometimes|nullable|numeric|min:0',
            'preco_max'  => 'sometimes|nullable|numeric|min:0|gte:preco_min',
            'em_estoque' => 'sometimes|nullable|boolean',
        ]);

        $filtros = [
            'search'     => $validados['search'] ?? null,
            'preco_min'  => $validados['preco_min'] ?? null,
            'preco_max'  => $validados['preco_max'] ?? null,
            'em_estoque' => isset($validados['em_estoque'])
                ? $request->boolean('em_estoque')
                : null,
        ];

        return ProdutoResource::collection(
            $this->service->listar($filtros)
        );
    }

    public function store(ProdutoRequest $request)
    {
        $produto = $this->service->create($request->validated());

        return (new ProdutoResource($produto))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Produto $produto)
    {
        return new ProdutoResource($produto);
    }

    public function update(ProdutoRequest $request, Produto $produto)
    {
        $produto = $this->service->update($produto, $request->validated());

        return new ProdutoResource($produto);
    }

    public function destroy(Produto $produto)
    {
        $this->service->delete($produto);

        return response()->noContent(); // 204
    }
}