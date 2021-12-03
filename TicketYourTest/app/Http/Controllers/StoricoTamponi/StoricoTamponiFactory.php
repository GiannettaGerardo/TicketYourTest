<?php

namespace App\Http\Controllers\StoricoTamponi;

use App\Http\Controllers\Attore;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class StoricoTamponiFactory extends Controller
{
    /** @var Int id dell'utente */
    private $idUtente;

    /** @var Int identificativo del tipo di attore */
    private $attore;


    /**
     * StoricoTamponiFactory constructor.
     * Usa la sessione per ottenere Id utente e tipo di attore
     */
    function __construct() {
        $this->idUtente = session()->get('LoggedUser');
        $this->attore = session()->get('Attore');
    }


    /**
     * Metodo factory per creare e restituire il tipo adeguato di oggetto StoricoTamponi
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createStoricoTamponi()
    {
        switch ($this->attore) {
            case Attore::CITTADINO_PRIVATO:
                $storicoTamponi = new StoricoTamponiCittadino($this->idUtente);
                break;
            case Attore::DATORE_LAVORO:
                $storicoTamponi = new StoricoTamponiDatoreLavoro($this->idUtente);
                break;
            case Attore::MEDICO_MEDICINA_GENERALE:
                $storicoTamponi = new StoricoTamponiMedicoMG($this->idUtente);
                break;
            default:
                $storicoTamponi = new StoricoTamponiCittadino($this->idUtente);
        }
        return $this->viewStoricoTamponi($storicoTamponi);
    }


    /**
     * Restituisce la vista per visualizzare lo storico dei tamponi
     * @param StoricoTamponi $storicoTamponi oggetto StoricoTamponi creato in precedenza
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    private function viewStoricoTamponi(StoricoTamponi $storicoTamponi)
    {
        $storicoPersonale = $storicoTamponi->getStoricoPersonale();
        $storicoPerTerzi = $storicoTamponi->getStoricoPerTerzi();
        return view('storicoTamponi', compact('storicoPersonale', 'storicoPerTerzi'));
    }


}
