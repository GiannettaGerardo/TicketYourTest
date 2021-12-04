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
     * Inserisce (o modifica) una transazione per il pagamento di un tampone.
     * @param int $id_prenotazione L'id della prenotazione
     * @param int $id_laboratorio L'id del laboratorio
     * @param double $importo L'importo della transazione
     * @param boolean $pagamento_online Se e' stato scelto il pagamento online
     * @param boolean $pagamento_effettuato Se e' stato effettuato il pagamento
     * @return void
     */
    static function upsertTransazione($id_prenotazione, $id_laboratorio, $importo, $pagamento_online=false, $pagamento_effettuato=false) {
        DB::table('transazioni')->upsert([
            'importo' => $importo,
            'id_prenotazione' => $id_prenotazione,
            'id_laboratorio' => $id_laboratorio,
            'pagamento_online' => $pagamento_online,
            'pagamento_effettuato' => $pagamento_effettuato
        ], ['id_prenotazione', 'id_laboratorio']);
    }
}
