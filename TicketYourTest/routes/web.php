<?php

use App\Http\Controllers\ListaDipendentiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfiloUtente;
use App\Models\DatoreLavoro;
use App\Models\User;
use App\Models\CittadinoPrivato;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
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
Route::get('/login', [LoginController::class, 'getLoginView'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.auth');
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
Route::get('/listaLaboratori', [AdminController::class, 'visualizzaLaboratoriNonConvenzionati'])->middleware('admin_registrato');
Route::post('/convenziona', [AdminController::class, 'convenzionaLaboratorio'])->middleware('admin_registrato')->name('convenziona.laboratorio');



/********************************************************
                Dashboard
***********************************************************/
Route::get('/profilo', [ProfiloUtente::class, 'visualizzaProfiloUtente'])->name('profiloUtente.visualizza')->middleware('cittadino_datore_medico_registrato');
Route::post('/profilo', [ProfiloUtente::class, 'modificaProfiloUtente'])->name('modifica.profilo');

/********************************************************
                Lista dipendenti
 ***********************************************************/
//richiesta di inserimento
Route::view('/richiediInserimento', 'richiediInserimento')->middleware('cittadino_registrato');
Route::post('/richiediInserimento', [ListaDipendentiController::class, 'richiediInserimento'])->middleware('cittadino_registrato')->name('richiedi.inserimento.lista');

//abbandono lista
//*********************QUI DOVREBBE STARE LA VIEW PER ACCEDERE ALLA LISTA DEI DIPENDENTI DA PARTE DEL CITTADINO*****************
Route::post('/abbandonaLista', [ListaDipendentiController::class, 'abbandona'])->middleware('cittadino_registrato')->name('abbandona.lista');

//lista dei dipendenti del datore
Route::get('/listaDipendenti', [ListaDipendentiController::class, 'visualizzaListaDipendenti'])->middleware('datore_registrato');

//inserimento di un dipendente nella lista
Route::post('/listaDipendenti/inserisci', [ListaDipendentiController::class, 'inserisciDipendente'])->middleware('datore_registrato')->name('inserisci.dipendente');

//visualizzazione richieste
Route::get('/richiesteInserimentoLista', [ListaDipendentiController::class, 'visualizzaRichieste'])->middleware('datore_registrato');

//Route relativa alla visualizzazione del form per l'aggiunta di un dipendente alla lista dipendenti (FABIO)
Route::get('/AggiungiDipendente', function () {
    return view('AggiungiDipendente');
});
