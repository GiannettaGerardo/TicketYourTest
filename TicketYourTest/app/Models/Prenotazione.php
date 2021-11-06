<?php

namespace App\Models;

use Carbon\Carbon;
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
     * Restituisce tutte le prenotazioni future (la cui data del tampone parte dalla data odierna) di un laboratorio, compresi i pazienti e i tipi di tampone che hanno prenotato.
     * Le prenotazioni vengono ordinate per data del tampone in ordine crescente
     * @param int $id_lab L'id del laboratorio
     * @return \Illuminate\Support\Collection
     */
    static function getInfoPrenotazioniFutureByIdLab($id_lab) {
        $pazienti = Paziente::getQueryForAllPazienti();

        return DB::table('prenotazioni')
            ->select(
                'prenotazioni.id as id_prenotazione',
                'prenotazioni.data_prenotazione as data_prenotazione',
                'prenotazioni.data_tampone as data_tampone',
                'prenotazioni.cf_prenotante as cf_prenotante',
                'prenotazioni.email as email_prenotante',
                'prenotazioni.numero_cellulare as numero_cellulare_prenotante',
                'laboratorio_analisi.id as id_laboratorio',
                'pazienti.cf_paziente as cf_paziente',
                'nome_paziente',
                'cognome_paziente',
                'email_paziente',
                'citta_residenza_paziente',
                'provincia_residenza_paziente',
                'esito_tampone',
                'tamponi.id as id_tampone',
                'tamponi.nome as nome_tampone',
                'token_scaduto'
            )
            ->fromSub($pazienti, 'pazienti')
            ->join('prenotazioni', 'prenotazioni.id', '=', 'pazienti.id_prenotazione')
            ->join('laboratorio_analisi', 'laboratorio_analisi.id', '=', 'prenotazioni.id_laboratorio')
            ->join('tamponi', 'tamponi.id', '=', 'prenotazioni.id_tampone')
            ->join('questionario_anamnesi', 'questionario_anamnesi.id_prenotazione', '=', 'prenotazioni.id')
            ->where('laboratorio_analisi.id', '=', $id_lab)
            ->where('prenotazioni.data_tampone', '>=', Carbon::now()->format('Y-m-d'))
            ->orderBy('prenotazioni.data_tampone', 'ASC')
            ->get();
    }


    /**
     * Generalizza le query select per ottenere i calendari prenotazioni
     * @return \Illuminate\Database\Query\Builder
     */
    static private function getPrenotazioniGenerale() {
        return DB::table('prenotazioni')
            ->join('pazienti', 'prenotazioni.id', '=', 'pazienti.id_prenotazione')
            ->join('laboratorio_analisi', 'laboratorio_analisi.id', '=', 'prenotazioni.id_laboratorio')
            ->join('tamponi', 'tamponi.id', '=', 'prenotazioni.id_tampone')
            ->join('questionario_anamnesi', 'questionario_anamnesi.id_prenotazione', '=', 'prenotazioni.id')
            ->whereRaw('DATE(prenotazioni.data_tampone) >= DATE(NOW())')
            ->select('pazienti.codice_fiscale as codice_fiscale',
                'pazienti.id_prenotazione as id_prenotazione'
            );
    }


    /**
     * Ritorna le prenotazioni future fatte da un utente per se stesso.
     * Nomi dei campi ritornati:
     * - data_prenotazione
     * - data_tampone
     * - nome_tampone
     * - laboratorio
     * @param $codice_fiscale // codice fiscale dell'utente
     * @return \Illuminate\Support\Collection
     */
    static function getPrenotazioni($codice_fiscale) {
        $query =  self::getPrenotazioniGenerale();
        return $query
            ->whereColumn('prenotazioni.cf_prenotante', 'pazienti.codice_fiscale')
            ->where('prenotazioni.cf_prenotante', $codice_fiscale)
            ->addSelect('prenotazioni.data_prenotazione as data_prenotazione',
                'prenotazioni.data_tampone as data_tampone',
                'tamponi.nome as nome_tampone',
                'laboratorio_analisi.nome as laboratorio',
                'questionario_anamnesi.token as token_questionario',
                'questionario_anamnesi.token_scaduto as token_questionario_scaduto'
            )
            ->get();
    }


    /**
     * Ritorna le prenotazioni future fatte da un utente per altre persone
     * Nomi dei campi ritornati:
     * - data_prenotazione
     * - data_tampone
     * - nome_tampone
     * - laboratorio
     * - nome_paziente
     * - cognome_paziente
     * @param $codice_fiscale // codice fiscale dell'utente che prenota per altre persone
     * @return \Illuminate\Support\Collection
     */
    static function getPrenotazioniPerTerzi($codice_fiscale) {
        $query =  self::getPrenotazioniGenerale();
        return $query
            ->where('prenotazioni.cf_prenotante', $codice_fiscale)
            ->whereColumn('prenotazioni.cf_prenotante', '<>', 'pazienti.codice_fiscale')
            ->addSelect('prenotazioni.data_prenotazione as data_prenotazione',
                'prenotazioni.data_tampone as data_tampone',
                'tamponi.nome as nome_tampone',
                'laboratorio_analisi.nome as laboratorio',
                'pazienti.nome as nome_paziente',
                'pazienti.cognome as cognome_paziente'
            )
            ->get();
    }


    /**
     * Ritorna le prenotazioni future fatte da altre persone per l'utente di cui si conosce il codice fiscale
     * Nomi dei campi ritornati:
     * - data_prenotazione
     * - data_tampone
     * - nome_tampone
     * - laboratorio
     * - nome_prenotante
     * - cognome_prenotante
     * @param $codice_fiscale // codice fiscale dell'utente a cui sono state fatte prenotazioni
     * @return \Illuminate\Support\Collection
     */
    static function getPrenotazioniDaTerzi($codice_fiscale) {
        $query =  self::getPrenotazioniGenerale();
        return $query
            ->join('users', 'users.codice_fiscale', '=', 'prenotazioni.cf_prenotante')
            ->where('pazienti.codice_fiscale', $codice_fiscale)
            ->whereColumn('prenotazioni.cf_prenotante', '<>', 'pazienti.codice_fiscale')
            ->addSelect('prenotazioni.data_prenotazione as data_prenotazione',
                'prenotazioni.data_tampone as data_tampone',
                'tamponi.nome as nome_tampone',
                'laboratorio_analisi.nome as laboratorio',
                'users.nome as nome_prenotante',
                'users.cognome as cognome_prenotante',
                'questionario_anamnesi.token as token_questionario',
                'questionario_anamnesi.token_scaduto as token_questionario_scaduto'
            )
            ->get();
    }


    /**
     * @return \Illuminate\Database\Query\Builder|mixed
     */
    static function getLastPrenotazione() {
        $max = DB::table('prenotazioni')->max('id');

        return DB::table('prenotazioni')
            ->find($max);
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


    /**
     * Elimina una prenotazione dal database
     * @param $id // identificativo univoco della prenotazione
     * @return int
     */
    static function deletePrenotazione($id)
    {
        return DB::table('prenotazioni')
            ->where('id', $id)
            ->delete();
    }
}
