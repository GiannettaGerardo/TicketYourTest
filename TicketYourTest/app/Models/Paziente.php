<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Paziente
 * Model della tabella del database
 */
class Paziente extends Model
{
    use HasFactory;

    /**
     * Inserisce un nuovo paziente nel database.
     * @param $id_prenotazione // L'id della prenotazione
     * @param $codice_fiscale // Il codice fiscale del paziente
     * @param null $nome // Il nome del paziente
     * @param null $cognome // Il cognome del paziente
     * @param null $email // L'email del paziente
     * @param null $citta_residenza // La citta' di residenza del paziente
     * @param null $provincia_residenza // La provincia di residenza del paziente
     * @return mixed // L'esito dell'inserimento del paziente nel database
     */
    static function insertNewPaziente($id_prenotazione, $codice_fiscale, $nome=null, $cognome=null, $email=null, $citta_residenza=null, $provincia_residenza=null) {
        return DB::table('pazienti')->insert([
            'id_prenotazione' => $id_prenotazione,
            'codice_fiscale' => $codice_fiscale,
            'nome' => $nome,
            'cognome' => $cognome,
            'email' => $email,
            'citta_residenza' => $citta_residenza
        ]);
    }


    /**
     * Restituisce la query per ottenere le informazioni di un paziente nella sua tabella specifica (diversa da quella del paziente).
     * @param string $table_name
     * @return \Illuminate\Database\Query\Builder
     */
    static private function getPazienteFromTable(string $table_name) {
        return DB::table($table_name)
            ->select(
                'pazienti.id_prenotazione as id_prenotazione',
                $table_name.'.codice_fiscale as cf_paziente',
                $table_name.'.nome as nome_paziente',
                $table_name.'.cognome as cognome_paziente',
                $table_name.'.email as email_paziente',
                $table_name.'.citta_residenza as citta_residenza_paziente',
                $table_name.'.provincia_residenza as provincia_residenza_paziente',
                'pazienti.risultato_comunicato_ad_asl_da_medico'
            )
            ->join('pazienti', $table_name.'.codice_fiscale', 'pazienti.codice_fiscale')
            ->whereNotNull($table_name.'.nome');
    }


    /**
     * Restituisce una query per tutti i pazienti registrati nel sistema.
     * @return \Illuminate\Database\Query\Builder
     */
    static function getQueryForAllPazienti() {
        /*
         * Prendo le informazioni dei pazienti dall'omonima tabella, dalla lista dei dipendenti
         * e dalle tabelle users e lista_dipendenti. Successivamente effettuo la union e restituisco il risultato.
         */
        $paziente_not_null = DB::table('pazienti')
            ->select(
                'id_prenotazione',
                'codice_fiscale as cf_paziente',
                'nome as nome_paziente',
                'cognome as cognome_paziente',
                'email as email_paziente',
                'citta_residenza as citta_residenza_paziente',
                'provincia_residenza as provincia_residenza_paziente',
                'risultato_comunicato_ad_asl_da_medico'
            )
            ->whereNotNull('nome');

        $utente = self::getPazienteFromTable('users');
        $dipendente_not_null = self::getPazienteFromTable('lista_dipendenti');

        return $paziente_not_null->union($utente)->union($dipendente_not_null);
    }


    /**
     * Restituisce la query per avere le informazioni sul paziente a partire dall'id della prenotazione
     * @param $id
     * @return \Illuminate\Database\Query\Builder
     */
    static function getQueryForPazienteByIdPrenotazione($id) {
        /*
         * Prendo le informazioni dei pazienti dall'omonima tabella, dalla lista dei dipendenti
         * e dalle tabelle users e lista_dipendenti. Successivamente, per ogni tabella effettuo il join con
         * la tabella prenotazioni e infine la union.
         */
        $paziente_not_null = DB::table('pazienti')
            ->select(
                'id_prenotazione',
                'codice_fiscale as cf_paziente',
                'nome as nome_paziente',
                'cognome as cognome_paziente',
                'email as email_paziente',
                'citta_residenza as citta_residenza_paziente',
                'provincia_residenza as provincia_residenza_paziente',
                'risultato_comunicato_ad_asl_da_medico'
            )
            ->whereNotNull('nome')
            ->where('id_prenotazione', '=', $id);

        $utente = self::getPazienteFromTable('users')->where('pazienti.id_prenotazione', '=', $id);
        $dipendente_not_null = self::getPazienteFromTable('lista_dipendenti')->where('pazienti.id_prenotazione', '=', $id);

        return $paziente_not_null->union($utente)->union($dipendente_not_null);
    }


    /**
     * Restituisce la prenotazione e il relativo paziente a partire dall'id della prenotazione.
     * @param int $id // L'id della prenotazione
     * @return Model|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection|object
     */
    static function getPrenotazioneEPazienteById($id) {
        $paziente = self::getQueryForPazienteByIdPrenotazione($id);

        return DB::table('prenotazioni')
            ->fromSub($paziente, 'paziente')
            ->join('prenotazioni', 'prenotazioni.id', 'paziente.id_prenotazione')
            ->select(
                'prenotazioni.id as id_prenotazione',
                'data_prenotazione',
                'id_tampone',
                'cf_prenotante',
                'prenotazioni.email as email_prenotante',
                'numero_cellulare as numero_cellulare_prenotante',
                'id_laboratorio',
                'cf_paziente',
                'nome_paziente',
                'cognome_paziente',
                'email_paziente',
                'citta_residenza_paziente',
                'provincia_residenza_paziente',
                'risultato_comunicato_ad_asl_da_medico'
            )->first();
    }


    /**
     * Restituisce i pazienti che ancora non hanno effettuato il tampone in un dato laboratorio.
     * @param $id_lab
     * @return \Illuminate\Support\Collection
     */
    static function getPazientiOdierniByIdLaboratorio($id_lab) {
        $pazienti = self::getQueryForAllPazienti();

        return DB::table('prenotazioni')
            ->fromSub($pazienti, 'pazienti')
            ->join('prenotazioni', 'prenotazioni.id', '=', 'pazienti.id_prenotazione')
            ->join('tamponi', 'tamponi.id', '=', 'prenotazioni.id_tampone')
            ->join('referti', 'referti.cf_paziente', '=', 'pazienti.cf_paziente')
            ->whereNull('referti.esito_tampone')
            ->where('prenotazioni.data_tampone', '<=', Carbon::now()->format('Y-m-d'))
            ->whereRaw('prenotazioni.id_laboratorio = ?', [$id_lab])
            ->orderBy('prenotazioni.data_tampone')
            ->select(
                'prenotazioni.id as id_prenotazione',
                'pazienti.cf_paziente as codice_fiscale',
                'pazienti.nome_paziente as nome',
                'pazienti.cognome_paziente as cognome',
                'tamponi.nome as nome_tampone'
            )
            ->get();
    }


    /**
     * Restituisce i pazienti di un medico a partire dalla sua email.
     * @param string $email_medico // L'email del medico
     * @return \Illuminate\Support\Collection
     */
    static function getPazientiByEmailMedico($email_medico) {
        $pazienti = self::getQueryForAllPazienti();

        return DB::table('pazienti')
            ->fromSub($pazienti, 'pazienti')
            ->join('prenotazioni', 'prenotazioni.id', '=', 'pazienti.id_prenotazione')
            ->join('questionario_anamnesi', function($join) {
                $join->on('questionario_anamnesi.id_prenotazione', '=', 'prenotazioni.id')
                    ->on('questionario_anamnesi.cf_paziente', '=', 'pazienti.cf_paziente');
            })
            ->where('questionario_anamnesi.email_medico', '=', $email_medico)
            ->select(
                'pazienti.nome_paziente',
                'pazienti.cognome_paziente',
                'pazienti.cf_paziente',
                'pazienti.risultato_comunicato_ad_asl_da_medico'
            )
            ->distinct()
            ->get();
    }


    /**
     * Elimina un singolo paziente di una singola prenotazione dal database
     * @param $codice_fiscale // codice fiscale del paziente
     * @param $id_prenotazione // identificativo univoco della prenotazione
     * @return int
     */
    static function deletePaziente($codice_fiscale, $id_prenotazione)
    {
        return DB::table('pazienti')
            ->where('codice_fiscale', $codice_fiscale)
            ->where('id_prenotazione', $id_prenotazione)
            ->delete();
    }


    /**
     * Salva la comunicazione del risultato del tampone all'asl da parte del
     * medico curante del paziente specificato
     * @param $codice_fiscale // paziente
     * @return int
     */
    static function updateRisultatoComunicatoASL($codice_fiscale)
    {
        return DB::table('pazienti')
            ->where('codice_fiscale', $codice_fiscale)
            ->update(['risultato_comunicato_ad_asl_da_medico' => 1]);
    }
}
