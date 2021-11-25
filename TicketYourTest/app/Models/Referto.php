<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Referto
 * Model che rappresenta la tabella 'referti'
 */
class Referto extends Model
{
    use HasFactory;


    /**
     * Crea o modifica un referto di un tampone.
     * @param int $id_prenotazione L'id della prenotazione
     * @param string $cf_paziente Il codice fiscale del paziente
     * @param null $esito_tampone L'esito del tampone
     * @param null $quantita La carica virale
     */
    static function upsertReferto($id_prenotazione, $cf_paziente, $esito_tampone=null, $quantita=null) {
        DB::table('referti')
            ->upsert([
                'id_prenotazione' => $id_prenotazione,
                'cf_paziente' => $cf_paziente,
                'esito_tampone' => $esito_tampone,
                'quantita' => $quantita
            ], ['id_prenotazione', 'cf_paziente']);
    }
}
