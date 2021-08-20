<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfiloUtente;
use App\Models\DatoreLavoro;
use App\Models\User;
use App\Models\CittadinoPrivato;
use App\Http\Controllers\AdminController;
use App\Models\ListaDipendentiController;
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

/********************************************************
                REGISTRAZIONE
***********************************************************/

//registrazione cittadino privato
Route::view('/registrazioneCittadino', 'registrazione',['categoriaUtente' => 'Cittadino privato']);
Route::post('/registrazioneCittadino', [RegisterController::class, 'cittadinoPrivatoRegister'])->name('registrazione.cittadino.richiesta');

//registrazione datore di lavoro
Route::view('/registrazioneDatore', 'registrazione',['categoriaUtente' => 'Datore di lavoro']);
Route::post('/registrazioneDatore', [RegisterController::class, 'datoreLavoroRegister'])->name('registrazione.datore.richiesta');

//registrazione medico curante
Route::view('/registrazioneMedico', 'registrazione',['categoriaUtente' => 'Medico curante']);
Route::post('/registrazioneMedico', [RegisterController::class, 'medicoMedicinaGeneraleRegister'])->name('registrazione.medico.richiesta');

//registrazione laboratorio analisi
Route::view('/registrazioneLaboratorio', 'registrazione',['categoriaUtente' => 'Laboratorio analisi']);
Route::post('/registrazioneLaboratorio', [RegisterController::class, 'laboratorioAnalisiRegister'])->name('registrazione.laboratorio.richiesta');

//convenzionamento laboratorio d'analisi
Route::view('/listaLaboratori', 'convenziona')->middleware('admin_registrato');
Route::post('/convenziona', [AdminController::class, 'convenzionaLaboratorioById'])->middleware('admin_registrato')->name('convenziona.laboratorio');



/********************************************************
                Dashboard
***********************************************************/
Route::get('/profilo', [ProfiloUtente::class, 'visualizzaProfiloUtente'])->name('profiloUtente.visualizza');

/********************************************************
                Lista dipendenti
 ***********************************************************/
Route::view('/richiediInserimento', 'richiediInserimento')->middleware('cittadino_registrato');
Route::post('/richiediInserimento', [ListaDipendentiController::class, 'richiediInserimento'])->middleware('cittadino_registrato')->name('richiedi.inserimento.lista');
