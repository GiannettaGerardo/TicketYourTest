<?php

namespace App\Http\Controllers;

use App\Models\Prenotazione;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ASLapi extends Controller
{

    public function notFound(Request $request)
    {
        return response()->json([
            'data' => 'API non trovata'
        ], 404);
    }

    /**
     * API per ritornare il numero di positivi suddivisi per data e regione.
     * Formato API:
     * {/
     *      "data1": {"regione1": positivi,
     *                "regione2": positivi,
     *                ...: ...            },
     *      "data2": {"regione1": positivi,
     *                "regione2": positivi,
     *                ...: ...            },
     *      ...
     * /}
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function getPositiviPerTempoESpazio()
    {
        $risultati_positivi = array();
        $api = array();
        $json = file_get_contents(storage_path() . '/../app/Utility/province-regioni.json');
        $regione_by_provincia = json_decode($json, true);

        try {
            $risultati_positivi = Prenotazione::getPositiviPerTempoEProvinciaLab();
        }
        catch(QueryException $ex) {
            return response()->json([
                'data' => 'Il database non risponde'
            ], 500);
        }

        // per ogni data, metto una regione che punta al numero di positivi, inoltre
        // conto tutti i positivi e li aggiunto nelle posizioni indicate dalla data e la regione
        foreach ($risultati_positivi as $rp) {
            if (isset($api[$rp->data][$regione_by_provincia[$rp->provincia]])){
                $api[$rp->data][$regione_by_provincia[$rp->provincia]] += $rp->positivi;
            }
            else {
                $api[$rp->data][$regione_by_provincia[$rp->provincia]] = $rp->positivi;
            }
        }

        return $api;
    }
}
