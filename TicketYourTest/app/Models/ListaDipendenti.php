<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ListaDipendenti extends Model
{
    use HasFactory;

    /**
     * Resituisce la lista di tutti i dipendenti di un certo luogo di lavoro identificato dalla partita iva.
     * In particolare restituisce:
     * - Codice fiscale
     * - Nome
     * - Cognome
     * - Email
     * - Citta' di residenza
     * - Provincia di residenza
     * I dipendenti restituiti sono solo quelli accettati dal datore di lavoro o inseriti direttamente da lui.
     * @param $partita_iva La partita iva del datore di lavoro di cui si vuole ottenere una lista.
     * @return \Illuminate\Support\Collection La lista.
     */
    static function getAllByPartitaIva($partita_iva) {
        // Dipendenti inseriti dal datore di lavoro
        $dipendenti = DB::table('lista_dipendenti')
            ->select([
                'codice_fiscale',
                'nome',
                'cognome',
                'email',
                'citta_residenza',
                'provincia_residenza'
            ])
            ->where('partita_iva_datore', $partita_iva)
            ->where('accettato', 1)
            ->whereNotNull('email');

        // Dipendenti iscritti e accettati
        return DB::table('users as us')
            ->select([
                'us.codice_fiscale as codice_fiscale',
                'us.nome as nome',
                'us.cognome as cognome',
                'us.email as email',
                'us.citta_residenza as citta_residenza',
                'us.provincia_residenza as provincia_residenza'
            ])
            ->join('lista_dipendenti as ld', 'us.codice_fiscale', '=', 'ld.codice_fiscale')
            ->where('accettato', '1')
            ->where('ld.partita_iva_datore', $partita_iva)
            ->union($dipendenti)
            ->get();
    }

    /**
     * Questo metodo cerca nelle tabelle user e lista_dipendenti un dipendente, tramite la partita iva del datore di lavoro
     * e il codice fiscale.
     * @param $partita_iva_datore
     * @param $codice_fiscale
     * @return Model|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection|object
     */
    static function getDipendenteByPartitaIvaECodiceFiscale($partita_iva_datore, $codice_fiscale) {
        $dipendente_non_registrato = DB::table('lista_dipendenti')
            ->select([
                'codice_fiscale',
                'nome',
                'cognome',
                'email',
                'citta_residenza',
                'provincia_residenza'
            ])
            ->where('partita_iva_datore', $partita_iva_datore)
            ->where('codice_fiscale', $codice_fiscale)
            ->where('accettato', 1)
            ->whereNotNull('email');

        // Dipendente iscritto e accettato
        return DB::table('users as us')
            ->select([
                'us.codice_fiscale as codice_fiscale',
                'us.nome as nome',
                'us.cognome as cognome',
                'us.email as email',
                'us.citta_residenza as citta_residenza',
                'us.provincia_residenza as provincia_residenza'
            ])
            ->join('lista_dipendenti as ld', 'us.codice_fiscale', '=', 'ld.codice_fiscale')
            ->where('accettato', '1')
            ->where('ld.partita_iva_datore', $partita_iva_datore)
            ->where('ld.codice_fiscale', $codice_fiscale)
            ->union($dipendente_non_registrato)
            ->first();
    }

    /**
     * Resituisce la lista di tutti gli utenti che hanno fatto richiesta di entrare in una lista dei dipendenti di un dato datore di lavoro
     * identificato mediante la partita iva.
     * In particolare restituisce:
     * - Codice fiscale
     * - Nome
     * - Cognome
     * - Email
     * - Citta' di residenza
     * - Provincia di residenza
     * I dipendenti restituiti sono solo quelli NON accettati dal datore di lavoro.
     * @param $partita_iva La partita iva del datore di lavoro di cui si vuole ottenere la lista.
     * @return \Illuminate\Support\Collection La lista.
     */
    static function getRichiesteInserimentoByPartitaIva($partita_iva_datore) {
        return DB::table('lista_dipendenti as ld')
            ->select([
                'us.codice_fiscale as codice_fiscale',
                'us.nome as nome',
                'us.cognome as cognome',
                'us.email as email',
                'us.citta_residenza as citta_residenza',
                'us.provincia_residenza as provincia_residenza'
            ])
            ->join('users as us', 'us.codice_fiscale', '=', 'ld.codice_fiscale')
            ->where('ld.partita_iva_datore', $partita_iva_datore)
            ->where('accettato', '0')
            ->get();
    }

    /**
     * Cerca le liste a cui un cittadino e' iscritto a partire dal suo codice fiscale.
     * @param $codice_fiscale Il codice fiscale del cittadino inserito nella lista dei dipendenti.
     * @return \Illuminate\Support\Collection
     */
    static function getListeByCodiceFiscale($codice_fiscale) {
        return DB::table('lista_dipendenti')
            ->select(['datore_lavoro.nome_azienda as nome_azienda', 'lista_dipendenti.partita_iva_datore as partita_iva'])
            ->join('datore_lavoro', 'lista_dipendenti.partita_iva_datore', '=', 'datore_lavoro.partita_iva')
            ->where('lista_dipendenti.codice_fiscale', $codice_fiscale)
            ->where('accettato', 1)
            ->get();
    }

    /**
     * Cerca, tramite il codice fiscale, tutte le liste di dipendenti a cui un cittadino e' iscritto o ha fatto richiesta.
     * @param $codice_fiscale Il codice fiscale del cittadino inserito nella lista dei dipendenti.
     * @return \Illuminate\Support\Collection
     */
    static function getAllListeByCodiceFiscale($codice_fiscale) {
        return DB::table('lista_dipendenti')
            ->select(['datore_lavoro.nome_azienda as nome_azienda', 'lista_dipendenti.partita_iva_datore as partita_iva'])
            ->join('datore_lavoro', 'lista_dipendenti.partita_iva_datore', '=', 'datore_lavoro.partita_iva')
            ->where('lista_dipendenti.codice_fiscale', $codice_fiscale)
            ->get();
    }

    /**
     * Inserisce un nuovo cittadino privato nella lista dei dipendenti.
     * @param $partita_iva_datore La partita iva del datore di lavoro.
     * @param $codice_fiscale Il codice fiscale del cittadino privato.
     * @param $accettato Se nella lista e' stato gia' accettato o no.
     * @return bool Se l'inserimento e' andato a buon fine.
     */
    static function insertNewCittadino($partita_iva_datore, $codice_fiscale, $accettato) {
        return DB::table('lista_dipendenti')->insert([
            'partita_iva_datore' => $partita_iva_datore,
            'codice_fiscale' => $codice_fiscale,
            'accettato' => $accettato
        ]);
    }

    /**
     * Elimina dalla lista dei dipendenti un dipendente o un cittadino.
     * @param $partita_iva La partita iva del datore di lavoro.
     * @param $codice_fiscale Il codice fiscale del cittadino che vuole abbandonare o del dipendente da eliminare.
     * @return int L'esito dell'eliminazione.
     */
    static function deleteDipendente($partita_iva, $codice_fiscale) {
        return DB::table('lista_dipendenti')
            ->where('partita_iva_datore', $partita_iva)
            ->where('codice_fiscale', $codice_fiscale)
            ->delete();
    }

    /**
     * Inserisce un dipendente con tutte le sue informazioni nella lista dei dipendenti.
     * Si presuppone che il dipendente sia gia' stato accettato nella lista.
     * @param $partita_iva_datore La partita iva del datore di lavoro.
     * @param $codice_fiscale Il codice fiscal del dipendente.
     * @param $nome Il nome del dipendente.
     * @param $cognome Il cognome del dipendente.
     * @param $email L'email del dipendente.
     * @param $citta_residenza La citta' di residenza del dipendente.
     * @param $provincia_residenza La provincia di residenza del dipendente.
     * @return bool L'esito dell'inserimento.
     */
    static function insertNewDipendente($partita_iva_datore, $codice_fiscale, $nome, $cognome, $email, $citta_residenza, $provincia_residenza) {
        return DB::table('lista_dipendenti')->insert([
            'partita_iva_datore' => $partita_iva_datore,
            'codice_fiscale' => $codice_fiscale,
            'nome' => $nome,
            'cognome' => $cognome,
            'email' => $email,
            'citta_residenza' => $citta_residenza,
            'provincia_residenza' => $provincia_residenza,
            'accettato' => '1'
        ]);
    }

    /**
     * Accetta un dipendente nella lista dei dipendenti a partire dalla partita iva e dal codice fiscale.
     * Cambia il valore dell'attributo 'accettato'.
     * @param $partita_iva_datore La partita iva del datore di lavoro.
     * @param $codice_fiscale Il codice fiscale del dipendente da accettare.
     * @return mixed
     */
    static function accettaDipendenteByCodiceFiscale($partita_iva_datore, $codice_fiscale) {
        return DB::table('lista_dipendenti')
            ->where('partita_iva_datore', $partita_iva_datore)
            ->where('codice_fiscale', $codice_fiscale)
            ->update(['accettato' => 1]);
    }

    /**
     * Elimina dalla lista la richiesta di un cittadino.
     * @param $partita_iva_datore La partita iva del datore di lavoro.
     * @param $codice_fiscale Il codice fiscale del dipendente da accettare.
     * @return int
     */
    static function rifiutaDipendenteByCodiceFiscale($partita_iva_datore, $codice_fiscale) {
        return DB::table('lista_dipendenti')
            ->where('partita_iva_datore', $partita_iva_datore)
            ->where('codice_fiscale', $codice_fiscale)
            ->delete();
    }
}
