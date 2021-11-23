<?php

namespace App\Http\Controllers;

use App\Models\Prenotazione;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ASLapi extends Controller
{
    /** @var string token da usare per l'API fornita dall'ASL per comunicare un nuovo positivo */
    private const TOKEN_PER_API_ASL = '9JFFfwefjjI4GIBViubfgu4BIBERV8bfhbr5649w84WF54F94F983frg';


    /**
     * Gestisce il caso in cui l'API richiesta sia inesistente
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound(Request $request)
    {
        return response()->json([
            'data' => 'API non trovata'
        ], 404);
    }


    /**
     * Utilizza l'API fornita dall'ASL al nostro sistema per comunicargli i positivi ai tamponi
     *
     * @param $codice_fiscale //del paziente positivo
     * @param $nome //del paziente positivo
     * @param $cognome //del paziente positivo
     * @param $citta_residenza //del paziente positivo
     * @param $provincia_residenza //del paziente positivo
     * @param $laboratorio_analisi //del laboratorio in cui è stata registrata la positività
     * @param $provincia_laboratorio //del laboratorio in cui è stata registrata la positività
     * @return \Illuminate\Http\Client\Response
     */
    public static function comunicaRisultatoTamponeAdASL($codice_fiscale, $nome, $cognome, $citta_residenza,
                                                         $provincia_residenza, $laboratorio_analisi, $provincia_laboratorio)
    {
        $url_api_asl = 'https://asl-api/'.self::TOKEN_PER_API_ASL.'/nuovo-positivo';

        // url di test
        $url_api_asl = 'http://127.0.0.1:8000/api/test-comunica-risultato-tampone-asl';
        // fine test

        return Http::post($url_api_asl, [
            'codice_fiscale' => $codice_fiscale,
            'nome' => $nome,
            'cognome' => $cognome,
            'citta_residenza' => $citta_residenza,
            'provincia_residenza' => $provincia_residenza,
            'laboratorio_analisi' => $laboratorio_analisi,
            'provincia_laboratorio' => $provincia_laboratorio,
        ]);
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
