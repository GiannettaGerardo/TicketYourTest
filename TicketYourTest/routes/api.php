<?php

use App\Http\Controllers\ASLapi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// tutte le route interne a questo gruppo avranno il middleware per il controllo del token per le API
Route::middleware(['api_tyt'])->group(function ()
{
    Route::get('/{token}/positiviPerTempoESpazio', [ASLapi::class, 'getPositiviPerTempoESpazio']);
    Route::get('/{token}/numeroTamponiGiornaliero', [ASLapi::class, 'getNumeroTamponiGiornalieri']);
    Route::get('/{token}/infoPazientiPositiviGiornalieri', [ASLapi::class, 'getPazientiPositiviGiornalieri']);

    // Questa route deve sempre essere l'ultima, gestisce le route inesistenti
    Route::any('/{token?}/{any?}', [ASLapi::class, 'notFound'])->where('any', '.*');
});

