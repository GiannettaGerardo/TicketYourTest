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
     * @param $id_prenotazione L'id della prenotazione
     * @param $codice_fiscale Il codice fiscale del paziente
     * @param null $nome Il nome del paziente
     * @param null $cognome Il cognome del paziente
     * @param null $email L'email del paziente
     * @param null $citta_residenza La citta' di residenza del paziente
     * @param null $provincia_residenza La provincia di residenza del paziente
     * @param null $questionario_anamnesi Il questionario anamnesi compilato dal paziente
     * @param null $esito_tampone L'esito del tampone
     * @return mixed L'esito dell'inserimento del paziente nel database
     */
    static function insertNewPaziente($id_prenotazione, $codice_fiscale, $nome=null, $cognome=null, $email=null, $citta_residenza=null, $provincia_residenza=null, $questionario_anamnesi=null, $esito_tampone=null) {
        return DB::table('pazienti')->insert([
            'id_prenotazione' => $id_prenotazione,
            'codice_fiscale' => $codice_fiscale,
            'nome' => $nome,
            'cognome' => $cognome,
            'email' => $email,
            'citta_residenza' => $citta_residenza,
            'provincia_residenza' => $provincia_residenza,
            'esito_tampone' => $esito_tampone
        ]);
    }


    /**
     * Restituisce la query per ottenere le informazioni di un paziente nella sua tabella specifica (diversa da quella del paziente).
     * @param string $table_name
     * @param $id_prenotazione
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
                'pazienti.esito_tampone as esito_tampone'
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
                'esito_tampone'
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
                'esito_tampone'
            )
            ->whereNotNull('nome')
            ->where('id_prenotazione', '=', $id);

        $utente = self::getPazienteFromTable('users')->where('pazienti.id_prenotazione', '=', $id);
        $dipendente_not_null = self::getPazienteFromTable('lista_dipendenti')->where('pazienti.id_prenotazione', '=', $id);

        return $paziente_not_null->union($utente)->union($dipendente_not_null);
    }


    /**
     * Restituisce la prenotazione e il relativo paziente a partire dall'id della prenotazione.
     * @param int $id L'id della prenotazione
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
                'esito_tampone'
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
            ->where('prenotazioni.data_tampone', '<=', Carbon::now()->format('Y-m-d'))
            ->where('pazienti.esito_tampone', '=', null)
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
     * Aggiorna l'esito del tampone di un paziente
     * @param $id_p // id della prenotazione
     * @param $cod_f // codice fiscale del paziente
     * @param $esito // esito del tampone (0 negativo, 1 positivo)
     * @return int
     */
    static function updateEsitoTampone($id_p, $cod_f, $esito)
    {
        return DB::table('pazienti')
            ->where('id_prenotazione', $id_p)
            ->where('codice_fiscale', $cod_f)
            ->update(['esito_tampone' => $esito]);
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
}
