<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfiloUtente;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/* Login e Logout */
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.auth');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

///////////////////////////// REGISTRAZIONE /////////////////////////////

//registrazione cittadino privato
Route::get('/registrazioneCittadino', function () {
    return view('paginaRegistrazione.registrazione',['categoriaUtente' => 'Cittadino privato']);
})->name('registrazione');
Route::post('/registrazioneCittadino', [RegisterController::class, 'cittadinoPrivatoRegister'])->name('registrazione.cittadino.richiesta');

//registrazione datore di lavoro
Route::get('/registrazioneDatore', function () {
    return view('paginaRegistrazione.registrazione',['categoriaUtente' => 'Datore di lavoro']);
})->name('registrazione');
Route::post('/registrazioneDatore', [RegisterController::class, 'datoreLavoroRegister'])->name('registrazione.datore.richiesta');

//registrazione medico curante
Route::get('/registrazioneMedico', function () {
    return view('paginaRegistrazione.registrazione',['categoriaUtente' => 'Medico curante']);
})->name('registrazione');
Route::post('/registrazioneMedico', [RegisterController::class, 'medicoMedicinaGeneraleRegister'])->name('registrazione.medico.richiesta');

//registrazione laboratorio analisi
Route::get('/registrazioneLaboratorio', function () {
    return view('paginaRegistrazione.registrazione',['categoriaUtente' => 'Laboratorio analisi']);
})->name('registrazione');
