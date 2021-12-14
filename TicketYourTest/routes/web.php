<?php

use App\Http\Controllers\ListaDipendentiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfiloUtente;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MappeController;
use App\Http\Controllers\ProfiloLaboratorio;
use App\Http\Controllers\TamponiController;
use App\Http\Controllers\PrenotazioniController;
use App\Http\Controllers\QuestionarioAnamnesiController;
use App\Http\Controllers\TransazioniController;
use App\Http\Controllers\RisultatiTamponiController;
use App\Http\Controllers\StoricoTamponi\StoricoTamponiFactory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagamentiContanti;
use Illuminate\Http\Request;

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
Route::get('/profiloLaboratorio', [ProfiloLaboratorio::class, 'getViewModifica'])->middleware('laboratorio_registrato')->name('profiloLab');
Route::post('/profiloLaboratorio/inserisciCalendario', [ProfiloLaboratorio::class, 'fornisciCalendarioDisponibilita'])->middleware('laboratorio_registrato')->name("inserisci.calendario.disponibilita");

//modifica dei tamponi offerti e del calendario disponibilita
Route::post('/profiloLaboratorio/modificaDati', [ProfiloLaboratorio::class, 'modificaLaboratorio'])->middleware('laboratorio_registrato')->name("modifica.dati.laboratorio");


/**************************************************************
        Laboraotri vicini
 **************************************************************/
Route::get('/laboratoriVicini/{tipoPrenotazione?}', [MappeController::class, 'getViewMappa'])->middleware('cittadino_datore_medico_registrato')->name('marca.laboratorii.vicini');
Route::post('/laboratoriVicini/disp', [MappeController::class, 'primoGiornoDisponibile'])->name("primo.giorno.disponibile");


/********************************************************
                    Prenotazione
 ***********************************************************/
Route::get('/prenotazione', [PrenotazioniController::class, 'visualizzaFormPrenotazione'])->name('form.prenotazione.singola')->middleware('form_prenotazione_visualizzabile');
Route::post('/prenotazione', [PrenotazioniController::class, 'prenota'])->name("prenotazione.singola")->middleware('form_prenotazione_visualizzabile');

Route::get('/prenotazione/per-terzi', [PrenotazioniController::class, 'visualizzaFormPrenotazionePerTerzi'])->name('form.prenotazione.terzi')->middleware('form_prenotazione_visualizzabile');
Route::post('/prenotazione/per-terzi', [PrenotazioniController::class, 'prenotaPerTerzi'])->name('prenotazione.terzi')->middleware('form_prenotazione_visualizzabile');

Route::get('/prenotazione/per-dipendenti', [PrenotazioniController::class, 'visualizzaFormPrenotazioneDipendenti'])->name('form.prenotazione.dipendenti')->middleware('form_prenotazione_dipendenti_visualizzabile');
Route::post('prenotazione/per-dipendenti', [PrenotazioniController::class, 'prenotaPerDipendenti'])->name('prenotazione.dipendenti')->middleware('form_prenotazione_dipendenti_visualizzabile');

Route::get('/elenco-prenotazioni', [PrenotazioniController::class, 'visualizzaElencoPrenotazioni'])->middleware('laboratorio_registrato')->name('form.prenotazione');


/********************************************************
               Questionario anamnesi
 ***********************************************************/
Route::get('/questionario-anamnesi-error', [QuestionarioAnamnesiController::class, 'visualizzaErroreQuestionarioAnamnesi'])->name('questionario.anamnesi.error');
Route::get('/questionario-anamnesi/{token}', [QuestionarioAnamnesiController::class, 'visualizzaFormQuestionarioAnamnesi'])->middleware('form_questionario_anamnesi_visualizzabile')->name('questionario.anamnesi');
Route::post('/questionario-anamnesi/{token}', [QuestionarioAnamnesiController::class, 'compilaQuestionario'])->middleware('form_questionario_anamnesi_visualizzabile')->name('compila.questionario.anamnesi');
Route::post('/visualizza-questionario-anamnesi', [QuestionarioAnamnesiController::class, 'questionarioCompilato'])->middleware('questionario_compilato_visualizzabile')->name('visualizza.questionario.anamnesi');


/***********************************************************
                 Calendario Prenotazione
 ***********************************************************/
Route::get('/calendarioPrenotazioni', [PrenotazioniController::class, 'visualizzaCalendariPrenotazione'])->name('calendario.prenotazioni')->middleware('cittadino_datore_medico_registrato');
Route::post('/calendarioPrenotazioni', [PrenotazioniController::class, 'annullaPrenotazioni'])->name('annulla.prenotazioni')->middleware('cittadino_datore_medico_registrato');


/***********************************************************
                Transazioni
 ***********************************************************/
Route::get('/checkout', [TransazioniController::class, 'visualizzaFormCheckout'])->name('visualizza.checkout')->middleware('form_checkout_visualizzabile');
Route::post('/checkout', [TransazioniController::class, 'checkout'])->name('pagamento.carta')->middleware('utente_registrato');


/***********************************************************
                Risultati tamponi
 ***********************************************************/
Route::get('/elenco-pazienti-odierni', [RisultatiTamponiController::class, 'visualizzaElencoPazientiOdierni'])->name('visualizza.elenco.pazienti.odierni')->middleware('laboratorio_registrato');
Route::post('/conferma-esito', [RisultatiTamponiController::class, 'confermaEsitoTampone'])->name('conferma.esito')->middleware('laboratorio_registrato');

Route::get('/refertoTampone/{id}', [RisultatiTamponiController::class, 'visualizzaReferto'])->name('referto.tampone');



/***********************************************************
                 Storico Prenotazioni
 ***********************************************************/
Route::get('/storicoPrenotazioni', function(){
    $storico = new StoricoTamponiFactory();
    return $storico->createStoricoTamponi();
})->name('storico.prenotazioni')->middleware('cittadino_datore_medico_registrato');

Route::post('/storicoPrenotazioniMedico', function(Request $request){
    $storico = new StoricoTamponiFactory();
    return $storico->eseguiPostRequest($request);
})->name('storico.prenotazioni.medico')->middleware('cittadino_datore_medico_registrato');


/***********************************************************
                 Registrazione Pagamento
 ***********************************************************/
Route::get('/registrazionePagamenti', [PagamentiContanti::class, 'getListaUtenti'])
    ->name('registrazione.pagamenti')->middleware('laboratorio_registrato');

Route::post('/registrazionePagamenti', [PagamentiContanti::class, 'salvaPagamento'])
    ->name('registrazione.pagamenti.registra')->middleware('laboratorio_registrato');
