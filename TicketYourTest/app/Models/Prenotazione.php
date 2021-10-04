<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
     * Ritorna le prenotazioni fatte da un utente per se stesso
     * @param $codice_fiscale
     * @return \Illuminate\Support\Collection
     */
    static function getPrenotazioni($codice_fiscale) {
        return DB::table('prenotazioni')
            ->join('pazienti', 'prenotazioni.cf_prenotante', '=', 'pazienti.codice_fiscale')
            ->where([
                ['prenotazioni.cf_prenotante', $codice_fiscale],
                ['pazienti.codice_fiscale', $codice_fiscale]
            ])
            ->get();
    }


    /*static function getPrenotazioniPerTerzi($codice_fiscale) {
        return DB::select(DB::raw(
            'SELECT pre.* FROM prenotazioni pre, pazienti paz '.
            'WHERE pre.cf_prenotante = :cf1 AND paz.codice_fiscale <> :cf1 AND pre.cf_prenotante <> paz.codice_fiscale'));
    }/*


    //TODO Ottenere prenotazione singola per avere l'id
    //TODO Aggiungere Model e migration per il paziente

    /**
     * Inserisce una nuova prenotazione di un dato tampone presso un certo laboratorio.
     * @param $data_prenotazione La data in cui e' stata effettuata la prenotazione
     * @param $data_tampone La data in cui e' previsto il tampone
     * @param $id_tampone L'id del tampone
     * @param $cf_prenotante Il codice fiscale di colui che ha prenotato
     * @param $id_lab L'id del laboratorio presso cui è stata effettuata la prenotazione
     * @return bool
     */
    //TODO Aggiungere email e numero di telefono
    static function insertNewPrenotazione($data_prenotazione, $data_tampone, $id_tampone, $cf_prenotante, $id_lab) {
        return DB::table('prenotazioni')
            ->insert([
                'data_prenotazione' => $data_prenotazione,
                'data_tampone' => $data_tampone,
                'id_tampone' => $id_tampone,
                'cf_prenotante' => $cf_prenotante,
                'id_laboratorio' => $id_lab
            ]);
    }
}
