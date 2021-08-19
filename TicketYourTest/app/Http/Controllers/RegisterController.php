<?php

namespace App\Http\Controllers;

use App\Models\CittadinoPrivato;
use App\Models\DatoreLavoro;
use App\Models\MedicoMG;
use App\Models\TamponiProposti;
use App\Models\User;
use App\Models\Laboratorio;
use App\Models\Tampone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Type;

/**
 * Class RegisterController
 * Controller per gestire le registrazioni dei diversi tipi di utenti
 * @package App\Http\Controllers
 */
class RegisterController extends Controller
{
    /**
     * Raggruppa le validazioni dei dati comuni a tutte le registrazioni
     * @param Request $request
     */
    private function validation(Request $request) {
        $validation = $request->validate([
            'cf' => 'required|min:16|max:16',
            'nome' => 'required|max:30',
            'cognome' => 'required|max:30',
            'citta_residenza' => 'required|max:50',
            'provincia_residenza' => 'required|max:50',
            'email' => 'required|email',
            'psw' => 'required|min:8|max:40',
            'psw-repeat' => 'required|min:8|max:40'
        ]);
    }

    /**
     * Raggruppa diversi dati presi in input dalle viste di registrazione
     * @param Request $request
     * @return array // array di dati di input
     */
    private function generalInput(Request $request) {
        $input = [];
        $input['codice_fiscale'] = $request->input('cf');
        $input['nome'] = $request->input('nome');
        $input['cognome'] = $request->input('cognome');
        $input['citta_residenza'] = $request->input('citta_residenza');
        $input['provincia_residenza'] = $request->input('provincia_residenza');
        $input['email'] = $request->input('email');
        $input['password'] = $request->input('psw');
        $input['password_repeat'] = $request->input('psw-repeat');
        return $input;
    }

    /**
     * Effettua la registrazione del cittadino privato
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cittadinoPrivatoRegister(Request $request)
    {
        $this->validation($request);

        $input = $this->generalInput($request);

        // check password inserita male in input
        if ($input['password'] !== $input['password_repeat']) {
            return back()->with('psw-repeat-error', 'la password ripetuta non corrisponde a quella inserita');
        }

        // check di esistenza pregressa dell'utente nel database
        $cittadino_esistente = CittadinoPrivato::getByEmail($input['email']);
        if ($cittadino_esistente) {
            return back()->with('email-already-exists', 'l\'email esiste già');
        }

        // inserimento nel database dei dati
        User::insertNewUtenteRegistrato($input['codice_fiscale'], $input['nome'], $input['cognome'], $input['citta_residenza'], $input['provincia_residenza'], $input['email'], $input['password']);
        CittadinoPrivato::insertNewCittadino($input['codice_fiscale']);

        return back()->with('register-success', 'Registrazione avvenuta con successo');
    }

    /**
     * Effettua la registrazione del medico di medicina generale
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function medicoMedicinaGeneraleRegister(Request $request)
    {
        $this->validation($request);
        $validation = $request->validate([ 'iva' => 'required|min:11|max:11' ]);

        $input = $this->generalInput($request);
        $input['partita_iva'] = $request->input('iva');

        // check password inserita male in input
        if ($input['password'] !== $input['password_repeat']) {
            return back()->with('psw-repeat-error', 'la password ripetuta non corrisponde a quella inserita');
        }

        // check di esistenza pregressa dell'utente nel database
        $medico_esistente = MedicoMG::getByEmail($input['email']);
        if ($medico_esistente) {
            return back()->with('email-already-exists', 'l\'email esiste già');
        }

        // inserimento nel database dei dati
        User::insertNewUtenteRegistrato($input['codice_fiscale'], $input['nome'], $input['cognome'], $input['citta_residenza'], $input['provincia_residenza'], $input['email'], $input['password']);
        MedicoMG::insertNewMedico($input['codice_fiscale'], $input['partita_iva']);

        return back()->with('register-success', 'Registrazione avvenuta con successo');
    }

    /**
     * Effettua la registrazione del datore di lavoro
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function datoreLavoroRegister(Request $request)
    {
        $this->validation($request);
        $validation = $request->validate([
            'iva' => 'required|min:11|max:11',
            'nome_azienda' => 'required|max:50',
            'citta_sede_aziendale' => 'required|max:40',
            'provincia_sede_aziendale' => 'required|max:40'
        ]);

        $input = $this->generalInput($request);
        $input['partita_iva'] = $request->input('iva');
        $input['nome_azienda'] = $request->input('nome_azienda');
        $input['citta_azienda'] = $request->input('citta_sede_aziendale');
        $input['provincia_azienda'] = $request->input('provincia_sede_aziendale');

        // check password inserita male in input
        if ($input['password'] !== $input['password_repeat']) {
            return back()->with('psw-repeat-error', 'la password ripetuta non corrisponde a quella inserita');
        }

        // check di esistenza pregressa dell'utente nel database
        $datore_esistente = DatoreLavoro::getByEmail($input['email']);
        if ($datore_esistente) {
            return back()->with('email-already-exists', 'l\'email esiste già');
        }

        // inserimento nel database dei dati
        User::insertNewUtenteRegistrato($input['codice_fiscale'], $input['nome'], $input['cognome'], $input['citta_residenza'], $input['provincia_residenza'], $input['email'], $input['password']);
        DatoreLavoro::insertNewDatore($input['codice_fiscale'], $input['partita_iva'], $input['nome_azienda'], $input['citta_azienda'], $input['provincia_azienda']);

        return back()->with('register-success', 'Registrazione avvenuta con successo');
    }

    /**
     * Effettua la registrazione e la richiesta di convenzionamento del medico di medicina generale.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function laboratorioAnalisiRegister(Request $request) {
        // Validazione dei dati
        $request->validate([
            'nomeLaboratorio' => 'max:30',
            'iva' => 'min:11|max:11',
            'provincia' => 'max:2',
            'citta' => 'max:30',
            'indirizzo' => 'max:50',
            'email' => 'email',
            'psw' => 'min:8|max:40',
            'psw-repeat' => 'min:8|max:40'
        ]);

        // Ottenimento input
        $input = $request->all();

        // Controllo sull'inserimento di almeno uno dei tamponi
        if(!isset($input['tamponeRapido']) and !isset($input['tamponeMolecolare'])) {
            return back()->with('tampone-non-scelto', 'Non e\' stato scelto nessun tampone!');
        }

        // Controllo sui prezzi del tampone
        if(isset($input['tamponeRapido']) and $input['costoTamponeRapido']==0.0) {
            return back()->with('costo-tampone-non-inserito', 'Non e\' stato inserito il costo del tampone');
        }
        if(isset($input['tamponeMolecolare']) and $input['costoTamponeMolecolare']==0.0) {
            return back()->with('costo-tampone-non-inserito', 'Non e\' stato inserito il costo del tampone');
        }

        // Controllo sulla password
        if($input['psw'] !== $input['psw-repeat']) {
            return back()->with('psw-repeat-error', 'la password ripetuta non corrisponde a quella inserita');
        }

        // Controllo sull'esistenza di una registrazione
        if(Laboratorio::getByEmail($input['email'])) {
            if(Laboratorio::isConvenzionatoByEmail($input['email'])) {
                return back()->with('email-already-exists', "Il laboratorio che si sta cercando di registrare e' gia' registrato e convenzionato al sistema!");
            } else {
                return back()->with('email-already-exists', "E' gia' presente una richiesta di convenzionamento con questa email!");
            }
        }

        // Se tutto e' andato a buon fine, viene effettuato il caricamento dei dati nel DB
        Laboratorio::insertNewLaboratorio(
            $input['iva'],
            $input['nome'],
            $input['citta'],
            $input['provincia'],
            $input['indirizzo'],
            $input['email'],
            $input['psw']
        );

        // Inserimento nel DB del/dei tampone/i
        if(isset($input['tamponeRapido'])) {
            $tampone = Tampone::getTamponeByNome('Tampone rapido');
            TamponiProposti::insertNewTamponeProposto($input['iva'], $tampone->id, $input['costoTamponeRapido']);
        }
        if(isset($input['tamponeMolecolare'])) {
            $tampone = Tampone::getTamponeByNome('Tampone molecolare');
            TamponiProposti::insertNewTamponeProposto($input['iva'], $tampone->id, $input['costoTamponeMolecolare']);
        }

        return back()->with('register-success', 'Registrazione avvenuta con successo. In attesa del convenzionamento!');
    }

    public function test(Request $request) {
        $tampone = Tampone::getTamponeByNome('Tampone rapido');
        echo $tampone->id;
    }
}
