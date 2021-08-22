<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ListaDipendenti extends Model
{
    use HasFactory;

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
     * Elimina dalla lista dei dipendenti un cittadino.
     * @param $partita_iva La partita iva del datore di lavoro.
     * @param $codice_fiscale Il codice fiscale del cittadino che vuole abbandonare.
     * @return int L'esito dell'eliminazione.
     */
    static function deleteCittadino($partita_iva, $codice_fiscale) {
        return DB::table('lista_dipendenti')->delete([$partita_iva, $codice_fiscale]);
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
}
