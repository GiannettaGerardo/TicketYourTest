<?php

namespace App\Http\Controllers;

use App\Models\Paziente;
use App\Models\Prenotazione;
use App\Models\Referto;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class ASLapi extends Controller
{
    /** @var String token da usare per l'API fornita dall'ASL per comunicare un nuovo positivo */
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
     * @param Collection $data contiene i seguenti dati, ottenibili tramite getters:
     * - codice fiscale paziente
     * - nome paziente
     * - cognome paziente
     * - città residenza paziente
     * - provincia residenza paziente
     * - nome laboratorio
     * - provincia laboratorio
     *
     * @return \Illuminate\Http\Client\Response
     */
    public static function comunicaRisultatoTamponeAdASL(Collection $data)
    {
        $url_api_asl = 'https://asl-api/'.self::TOKEN_PER_API_ASL.'/nuovo-positivo';

        // url di test
        $url_api_asl = 'http://127.0.0.1:8000/api/test-comunica-risultato-tampone-asl';
        // fine test

        return Http::post($url_api_asl, [
            'codice_fiscale' => $data->cf_paziente,
            'nome' => $data->nome_paziente,
            'cognome' => $data->cognome_paziente,
            'citta_residenza' => $data->citta_residenza_paziente,
            'provincia_residenza' => $data->provincia_residenza_paziente,
            'laboratorio_analisi' => $data->nome_laboratorio,
            'provincia_laboratorio' => $data->provincia_laboratorio
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


    /**
     * Funzione dell'API per restituire il numero di tamponi in data odierna in formato json.
     * L'informazione si aggiorna ogni 24 ore.
     * Il formato dei dati e' spiegato tramite il seguente esempio:
     * {
     *      "data_riferimento": "2021-11-26",
     *      "numero_tamponi": 150
     * }
     *
     * NOTA: Viene restituito solo il numero di tamponi certificati tramite referto e la data, come da esempio, segue il formato Y-m-d.
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function getNumeroTamponiGiornalieri() {
        $oggi = Carbon::now()->format('Y-m-d');
        $numero_tamponi = null;
        $result = [];

        try {
            $numero_tamponi = Referto::getNumeroTamponiByGiorno($oggi);
            $result = [
                'data_riferimento' => $oggi,
                'numero_tamponi' => $numero_tamponi
            ];
        }
        catch(QueryException $ex) {
            return response()->json([
                'data' => 'Il database non risponde'
            ], 500);
        }

        return json_encode($result);
    }


    /**
     * Funzione dell'API per restituire i dettagli dei pazienti che sono risultati positivi al tampone rapido o al tampone molecolare.
     * L'informazione si aggiorna ogni 24 ore.
     * Il formato dei dati è spiegato tramite il seguente esempio:
     * {
     *      "codice_fiscale_paziente": "RSSMRO87B09H501R",
     *      "nome_paziente": "Mario",
     *      "cognome_paziente": "Rossi",
     *      ...
     * }
     *
     * NOTA: Vengono restituiti solo i pazienti i cui tamponi sono certificati tramite referto e la data, come da esempio, segue il formato Y-m-d.
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function getPazientiPositiviGiornalieri() {
        $oggi = Carbon::now()->format('Y-m-d');
        $result = null;

        try {
            $result = Paziente::getPazientiPositiviByGiorno($oggi);
        }
        catch(QueryException $ex) {
            return response()->json([
                'data' => 'Il database non risponde'
            ], 500);
        }

        return json_encode($result);
    }
}
