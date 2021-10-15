<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
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
            'questionario_anamnesi' => $questionario_anamnesi,
            'esito_tampone' => $esito_tampone
        ]);
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
