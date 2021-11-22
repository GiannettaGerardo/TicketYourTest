<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class TransazioniController
 * Controller per gestire le transazioni tra utenti e laboratori
 * @package App\Http\Controllers
 */
class TransazioniController extends Controller
{
    public function visualizzaFormCheckout(Request $request, $prenotazioni) {
        $tot = 0;
        foreach($prenotazioni as $prenotazione) {
            echo $prenotazione['nome_paziente'] . ', ' .
                $prenotazione['cognome_paziente'] . ', ' .
                $prenotazione['nome_laboratorio'] . ', ' .
                $prenotazione['tampone'] . ', ' .
                $prenotazione['costo_tampone'];
            $tot += (double) $prenotazione['costo_tampone'];
            echo '</br>';
        }
        echo '<p>Totale: ' . $tot .'</p>';
    }
}
