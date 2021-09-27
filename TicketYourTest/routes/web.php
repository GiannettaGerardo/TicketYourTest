<?php

use App\Http\Controllers\ListaDipendentiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfiloUtente;
use App\Models\DatoreLavoro;
use App\Models\User;
use App\Models\CittadinoPrivato;
use App\Models\Tampone;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MappeController;
use App\Http\Controllers\ProfiloLaboratorio;
use App\Http\Controllers\TamponiController;
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
})->name("home");

//guida tamponi
Route::get('/guidaTamponi', [TamponiController::class, 'visualizzaGuidaUnica'])->name('visualizza.guida.unica');

/* Login e Logout */
Route::get('/login', [LoginController::class, 'getLoginView'])->middleware('login_effettuato')->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.auth');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

/********************************************************
                REGISTRAZIONE
 ***********************************************************/

//registrazione cittadino privato
Route::view('/registrazioneCittadino', 'registrazione', ['categoriaUtente' => 'Cittadino privato'])->middleware('login_effettuato')->name('registrazione.cittadino');
Route::post('/registrazioneCittadino', [RegisterController::class, 'cittadinoPrivatoRegister'])->name('registrazione.cittadino.richiesta');

//registrazione datore di lavoro
Route::view('/registrazioneDatore', 'registrazione', ['categoriaUtente' => 'Datore di lavoro'])->middleware('login_effettuato')->name('registrazione.datore');
Route::post('/registrazioneDatore', [RegisterController::class, 'datoreLavoroRegister'])->name('registrazione.datore.richiesta');

//registrazione medico curante
Route::view('/registrazioneMedico', 'registrazione', ['categoriaUtente' => 'Medico curante'])->middleware('login_effettuato')->name('registrazione.medico');
Route::post('/registrazioneMedico', [RegisterController::class, 'medicoMedicinaGeneraleRegister'])->name('registrazione.medico.richiesta');

//registrazione laboratorio analisi
Route::get('/registrazioneLaboratorio', [RegisterController::class, 'visualizzaRegistrazioneLaboratorio'])->middleware('login_effettuato')->name('registrazione.laboratorio');
Route::post('/registrazioneLaboratorio', [RegisterController::class, 'laboratorioAnalisiRegister'])->name('registrazione.laboratorio.richiesta');

//convenzionamento laboratorio d'analisi
Route::get('/listaLaboratori', [AdminController::class, 'visualizzaLaboratoriNonConvenzionati'])->middleware('admin_registrato')->name("convenziona.laboratorio.vista");
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
Route::get('/richiediInserimento', [ListaDipendentiController::class, 'visualizzaInserimentoInLista'])->middleware('cittadino_registrato')->name('richiedi.inserimento.lista.vista');
Route::post('/richiediInserimento', [ListaDipendentiController::class, 'richiediInserimento'])->middleware('cittadino_registrato')->name('richiedi.inserimento.lista');

//abbandono lista
Route::get('/listeDipendentiCittadino', [ListaDipendentiController::class, 'visualizzaListeDipendentiCittadino'])->middleware('cittadino_registrato')->name('abbandona.lista.vista');
Route::post('/abbandonaLista', [ListaDipendentiController::class, 'abbandona'])->middleware('cittadino_registrato')->name('abbandona.lista');

//lista dei dipendenti del datore
Route::get('/listaDipendenti', [ListaDipendentiController::class, 'visualizzaListaDipendenti'])->middleware('datore_registrato')->name('visualizza.lista.dipendenti');

//Eliminazione di un dipendente dalla lista
Route::post('/listaDipendenti', [ListaDipendentiController::class, 'deleteDipendente'])->middleware('datore_registrato')->name('elimina.dipendente');

//inserimento di un dipendente nella lista
Route::get('/listaDipendenti/inserisci', [ListaDipendentiController::class, 'visualizzaInserisciDipendente'])->middleware('datore_registrato')->name('inserisci.dipendente.form');
Route::post('/listaDipendenti/inserisci', [ListaDipendentiController::class, 'inserisciDipendente'])->middleware('datore_registrato')->name('inserisci.dipendente');

//visualizzazione richieste
Route::get('/richiesteInserimentoLista', [ListaDipendentiController::class, 'visualizzaRichieste'])->middleware('datore_registrato')->name('richieste.inserimento.lista');

//accetta e rifiuta richiesta
Route::post('/richiesteInserimentoLista/accetta', [ListaDipendentiController::class, 'accettaRichiestaDipendente'])->middleware('datore_registrato')->name('accetta.dipendente');
Route::post('/richiesteInserimentoLista/rifiuta', [ListaDipendentiController::class, 'rifiutaRichiestaDipendente'])->middleware('datore_registrato')->name('rifiuta.dipendente');



/********************************************************
                Dashboard laboratori
 ***********************************************************/

//primo inserimento del calendario disponbilita
Route::get('/profiloLaboratorio', [ProfiloLaboratorio::class, 'getViewModifica'])->name('profiloLab');
Route::post('/profiloLaboratorio/inserisciCalendario', [ProfiloLaboratorio::class, 'fornisciCalendarioDisponibilita'])->name("inserisci.calendario.disponibilita");

//modifica dei tamponi offerti e del calendario disponibilita
Route::post('/profiloLaboratorio/modificaDati', [ProfiloLaboratorio::class, 'modificaLaboratorio'])->name("modifica.dati.laboratorio");


/**************************************************************
        Laboraotri vicini
 **************************************************************/
Route::get('/laboratoriVicini', [MappeController::class, 'getViewMappa'])->middleware('cittadino_datore_medico_registrato')->name('marca.laboratorii.vicini');
