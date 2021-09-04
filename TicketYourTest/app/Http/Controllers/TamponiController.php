<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tampone;

/**
 * Class TamponiController.
 * Classe che contiene i metodi per gestire le viste riguardanti i tamponi
 */
class TamponiController extends Controller
{
    /**
     * Ottiene le informazioni sui tamponi e restituisce la view contenente queste informazioni.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaGuidaUnica() {
        $tampone_rapido = Tampone::getTamponeByNome('Tampone rapido');
        $tampone_molecolare = Tampone::getTamponeByNome('Tampone molecolare');
        return view('guidaunica', compact('tampone_molecolare', 'tampone_rapido'));
    }
}
