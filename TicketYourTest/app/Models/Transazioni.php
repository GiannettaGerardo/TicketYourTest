<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Transazioni
 * Model della tabella del database
 */
class Transazioni extends Model
{
    use HasFactory;


    /**
     * Inserisce nel database una nuova transazione
     * @param int $id_prenotazione
     * @param int $id_laboratorio
     * @param double $importo
     */
    static function insertNewTransazione($id_prenotazione, $id_laboratorio, $importo) {
        DB::table('transazioni')->insert([
            'importo' => $importo,
            'id_prenotazione' => $id_prenotazione,
            'id_laboratorio' => $id_laboratorio
        ]);
    }
}
