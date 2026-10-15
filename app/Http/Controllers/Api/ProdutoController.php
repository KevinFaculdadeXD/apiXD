<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutoRequest;
use App\Http\Resources\ProdutoResource;
use App\Models\Produtos;
use App\Services\ProdutoService;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{

    public function __construct(private ProdutoService $service)
    {

    }


    public function index()
    {
        return ProdutoResource::collection(Produtos::latest()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProdutoRequest $request)
    {

        $produto = $this->service->create($request->validated());

        return (new ProdutoResource($produto))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produtos $produto)
    {
        return new ProdutoResource($produto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProdutoRequest $request, Produtos $produto)
{
    $dados = $request->validated();

    $produto->update($dados);

    return new ProdutoResource($produto);
}


    public function destroy(Produtos $produto)
    {
        $produto->delete();
        return response()->noContent(); // 204

    }

public function validarCusto(
){

}
}