<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Prenotazione
 * Model della tabella del database
 */
class Prenotazione extends Model
{
    use HasFactory;


    /**
     * Ritorna il numero di prenotazioni di un laboratorio in una certa data
     * @param $id_lab // id del laboratorio
     * @param $data   // data di tipo stringa in formato 'Y-m-d'
     * @return int
     */
    static function getPrenotazioniByIdEData($id_lab, $data) {
        $risultato = DB::table('prenotazioni')
            ->selectRaw('count(*) as prenotazioni')
            ->whereRaw('id_laboratorio = ? and DATE(data_tampone) = ?', [$id_lab, $data])
            ->get();

        return intval($risultato[0]->prenotazioni);
    }

    /**
     * Restituisce una prenotazione a partire dal suo id
     * @param $id
     * @return \Illuminate\Database\Query\Builder|mixed
     */
    static function getPrenotazioneById($id) {
        return DB::table('prenotazioni')->find($id);
    }

    /**
     * Ritorna le prenotazioni fatte da un utente per se stesso
     * @param $codice_fiscale // codice fiscale dell'utente
     * @return \Illuminate\Support\Collection
     */
    static function getPrenotazioni($codice_fiscale) {
        return DB::table('prenotazioni')
            ->join('pazienti', 'prenotazioni.id', '=', 'pazienti.id_prenotazione')
            ->whereColumn('prenotazioni.cf_prenotante', 'pazienti.codice_fiscale')
            ->where('prenotazioni.cf_prenotante', $codice_fiscale)
            ->get();
    }


    /**
     * Ritorna le prenotazioni fatte da un utente per altre persone
     * @param $codice_fiscale // codice fiscale dell'utente che prenota per altre persone
     * @return \Illuminate\Support\Collection
     */
    static function getPrenotazioniPerTerzi($codice_fiscale) {
         return DB::table('prenotazioni')
            ->join('pazienti', 'prenotazioni.id', '=', 'pazienti.id_prenotazione')
            ->where('prenotazioni.cf_prenotante', $codice_fiscale)
            ->whereColumn('prenotazioni.cf_prenotante', '<>', 'pazienti.codice_fiscale')
            ->get();
    }


    /**
     * Ritorna le prenotazioni fatte da altre persone per l'utente di cui si conosce il codice fiscale
     * @param $codice_fiscale // codice fiscale dell'utente a cui sono state fatte prenotazioni
     * @return \Illuminate\Support\Collection
     */
    static function getPrenotazioniDaTerzi($codice_fiscale) {
        return DB::table('prenotazioni')
            ->join('pazienti', 'prenotazioni.id', '=', 'pazienti.id_prenotazione')
            ->where('pazienti.codice_fiscale', $codice_fiscale)
            ->whereColumn('prenotazioni.cf_prenotante', '<>', 'pazienti.codice_fiscale')
            ->get();
    }


    /**
     * Inserisce una nuova prenotazione di un dato tampone presso un certo laboratorio.
     * @param $data_prenotazione La data in cui e' stata effettuata la prenotazione
     * @param $data_tampone La data in cui e' previsto il tampone
     * @param $id_tampone L'id del tampone
     * @param $cf_prenotante Il codice fiscale di colui che ha prenotato
     * @param $id_lab L'id del laboratorio presso cui è stata effettuata la prenotazione
     * @return bool
     */
    static function insertNewPrenotazione($data_prenotazione, $data_tampone, $id_tampone, $cf_prenotante, $email, $numero_cellulare, $id_lab) {
        return DB::table('prenotazioni')
            ->insert([
                'data_prenotazione' => $data_prenotazione,
                'data_tampone' => $data_tampone,
                'id_tampone' => $id_tampone,
                'cf_prenotante' => $cf_prenotante,
                'email' => $email,
                'numero_cellulare' => $numero_cellulare,
                'id_laboratorio' => $id_lab
            ]);
    }


    /**
     * Controlla l'esistenza di una prenotazione confrontando il codice fiscale del prenotante,
     * il codice fiscale del paziente, l'id e la data del tampone e il laboratorio.
     * @param $codice_fiscale_prenotante Il codice fiscale del prenotante
     * @param $codice_fiscale_paziente Il codice fiscale del paziente
     * @param $id_tampone L'id del tampone
     * @param $data_tampone La data in cui e' fissato il tampone
     * @param $id_lab Il laboratorio presso cui e' stato prenotato il tampone
     * @return bool true se la query restituisce un risultato (quindi esiste la prenotazione), false altrimenti.
     */
    static function existsPrenotazione($codice_fiscale_prenotante, $codice_fiscale_paziente, $id_tampone, $data_tampone, $id_lab) {
        $prenotazione = DB::table('prenotazioni')
            ->join('pazienti', 'prenotazioni.id', '=', 'pazienti.id_prenotazione')
            ->where('prenotazioni.cf_prenotante', '=', $codice_fiscale_prenotante)
            ->where('pazienti.codice_fiscale', '=', $codice_fiscale_paziente)
            ->where('prenotazioni.id_tampone', '=', $id_tampone)
            ->where('prenotazioni.data_tampone', '=', $data_tampone)
            ->where('prenotazioni.id_laboratorio', '=', $id_lab)
            ->get();

        return !$prenotazione->isEmpty();   // Se e' vuoto, la prenotazione esiste, quindi restituisce true
    }
}
