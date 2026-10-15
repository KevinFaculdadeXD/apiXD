<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClimaController extends Controller
{
public function show()
{
    $resposta = http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude'              => '-23.42',
            'longitude'             => '-51.93',
            'current_weather'       => 'true',
    ]);

    if ($resposta->failed()) {
        return response()->json(['erro' => 'API do clima não está disponivel'], 502);
    }

    $atual = $resposta->json('current_weather');

    return response()->json([
        'temperatura_c' => $atual['temperature'],
        'vento_kmh'     => $atual['windspeed'],
        'horario'       =>$atual['time'],
    ]);
}
}
