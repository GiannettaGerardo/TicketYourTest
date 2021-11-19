<?php


namespace App\Utility;
use App\Models\Italia as Ita;
use Illuminate\Database\QueryException;

/* la libreria per curl non funzionerà immediatamente, siamo costretti
 * ad installare curl per php
 * (da linea di comando per linux): sudo apt-get install php-curl */

class Italia {

    /** tutte le regioni italiane */
    private $regioni = array();
    /** tutte le regioni italiane con tutte le relative province */
    private $italia = array();
    /** tutte le province italiane, associate alla loro regione,
     * così come sono memorizzate nel database */
    private $italiaDB = array();


    /**
     * Costruttore
     * Inizializza l'array $regioni
     */
    function __construct()
    {
        $this->regioni = [
            'abruzzo',
            'basilicata',
            'calabria',
            'campania',
            'emilia-romagna',
            'friuli-venezia-giulia',
            'lazio',
            'liguria',
            'lombardia',
            'marche',
            'molise',
            'piemonte',
            'puglia',
            'sardegna',
            'sicilia',
            'toscana',
            'trentino-alto-adige',
            'umbria',
            'valle-d-aosta',
            'veneto'
        ];
    }


    /**
     * Inizializza l'array $italia contenente per ogni regione, un sottoarray delle province
     */
    public function createItaly()
    {
        $this->italia = array(); // azzera il contenuto precedente

        foreach ($this->regioni as $regione) {
            $this->italia[$regione] = self::tuttitaliaScraping_GetProvinceByRegione($regione);
        }
    }


    /**
     * Ottiene tutte le province di una $regione applicando la tecnica del web scraping
     * sul sito www.tuttitalia.it
     * @param $regione // regione di cui trovare le province
     * @return array di province della regione $regione
     */
    private static function tuttitaliaScraping_GetProvinceByRegione($regione)
    {
        $first_match = array();
        $final_array = array();
        $i = 0;

        // web scraping
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.tuttitalia.it/'.$regione.'/');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For HTTPS
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // For HTTPS
        $response = curl_exec($ch);
        curl_close($ch);

        // uso di espressioni regolari per filtrare il risultato
        preg_match_all('/<div class=\"ow\">[A-Z]{2}/', $response, $first_match);
        // creazione di un array con ulteriore filtraggio sulle 2 lettere maiuscole
        // della provincia tramite espressioni regolari
        foreach ($first_match[0] as $single_match) {
            preg_match_all('/[A-Z]{2}/', $single_match, $final_array[$i]);
            $final_array[$i] = $final_array[$i][0][0];
            $i++;
        }

        return $final_array;
    }


    /**
     * Crea una formattazione utile per memorizzare tutto l'array italia in una tabella del database
     * @return array nella forma [0] => ['provincia' => $provincia, 'regione' => $regione],
     *                           [1] => ['provincia' => $provincia, 'regione' => $regione],
     *                           ...
     * @return null se l'attributo di classe $italia non è stato precendetemente riempito
     */
    public function createDataForDBTableInsert()
    {
        if (count($this->italia) === 0) {
            return null;
        }

        $formattazione_DB = array();
        $i = 0;

        foreach ($this->italia as $regione => $province) {
            foreach ($province as $provincia) {
                $formattazione_DB[$i++] = [
                    'provincia' => $provincia,
                    'regione' => $regione
                ];
            }
        }

        return $formattazione_DB;
    }


    /**
     * Riempie l'array di classe $italiaDB dal database, utilizzando
     * la classe model Italia
     */
    public function getItaliaDB_fromDB()
    {
        $this->italiaDB = array(); // azzera il contenuto precedente
        $all = null;
        try {
            $all = Ita::getProvinceRegioni();
        }
        catch(QueryException $ex) {
            abort(500, 'Il database non risponde.');
        }

        foreach ($all as $tupla) {
            $this->italiaDB[$tupla->provincia] = $tupla->regione;
        }
    }


    /**
     * Ritorna l'array di classe $italiDB
     * @return array in forma [$provincia] => [$regione],
     *                        [$provincia] => [$regione],
     *                        ...
     * @return null se l'array di classe $italiaDB non è riempito
     */
    public function getItaliaDB()
    {
        if (count($this->italiaDB) === 0) {
            return null;
        }
        return $this->italiaDB;
    }
}
