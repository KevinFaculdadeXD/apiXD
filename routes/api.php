<?php

use App\Http\Controllers\Api\ProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClimaController;

Route::apiResource('produtos', ProdutoController::class);
Route::get('clima', [ClimaController::class, 'show']);


/*
GET         
POST        
SHOW
PUT
*/