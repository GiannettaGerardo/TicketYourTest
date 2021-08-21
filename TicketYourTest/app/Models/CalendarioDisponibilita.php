<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalendarioDisponibilita extends Model
{
    use HasFactory;

    /**
     * Ritorna il calendario disponibilità di un laboratorio
     * @param $id_laboratorio
     * @return \Illuminate\Support\Collection
     */
    static function getCalendarioDisponibilitaByIdLaboratorio($id_laboratorio) {
        return DB::table('calendario_disponibilita')
            ->where('id_laboratorio', $id_laboratorio)->get();
    }

    /**
     * Inserisce il calendario disponibilità di un laboratorio all'interno dell'apposita tabella del db
     * @param $id_laboratorio
     * @param $calendario // array così formato:
     * $calendario['lunedi']['ora_inizio'] = 10
     * $calendario['lunedi']['ora_fine'] = 22
     * <---> $calendario['lunedi'] = ['ora_inizio' => 10, 'ora_fine' => 22]
     */
    static function insertCalendarioPerLaboratorio($id_laboratorio, $calendario) {
        $data = [];
        $i = 0;
        foreach ($calendario as $giorno_settimana => $array_orari) {
            $data[$i++] = [
                'id_laboratorio' => $id_laboratorio,
                'giorno_settimana' => $giorno_settimana,
                'ora_inizio' => $array_orari['ora_inizio'],
                'ora_fine' => $array_orari['ora_fine']
            ];
        }
        DB::table('calendario_disponibilita')->insert($data);
    }
}
