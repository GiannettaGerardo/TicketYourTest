<?php

namespace App\Models\LaboratorioModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalendarioDisponibilita extends Model
{
    use HasFactory;

    /**
     * Ritorna il calendario disponibilit√† di un laboratorio
     * @param $id_laboratorio
     * @return \Illuminate\Support\Collection
     */
    static function getCalendarioDisponibilitaByIdLaboratorio($id_laboratorio) {
        return DB::table('calendario_disponibilita')
            ->where('id_laboratorio', $id_laboratorio)->get();
    }


    /**
     * Inserisce il calendario disponibilit√† di un laboratorio all'interno dell'apposita tabella del db
     * @param $id_laboratorio
     * @param $calendario // array cos√¨ formato:
     * $calendario['lunedi']['oraApertura'] = 10
     * $calendario['lunedi']['oraChiusura'] = 22
     * <---> $calendario['lunedi'] = ['oraApertura' => 10, 'oraChiusura' => 22]
     */
    static function upsertCalendarioPerLaboratorio($id_laboratorio, $calendario) {
        $data = [];
        $i = 0;
        foreach ($calendario as $giorno_settimana => $array_orari) {
            $data[$i++] = [
                'id_laboratorio' => $id_laboratorio,
                'giorno_settimana' => $giorno_settimana,
                'oraApertura' => $array_orari['oraApertura'],
                'oraChiusura' => $array_orari['oraChiusura']
            ];
        }
        DB::table('calendario_disponibilita')->upsert($data, ['id_laboratorio', 'giorno_settimana'], ['oraApertura', 'oraChiusura']);
    }


    static function deleteGiorniCalendario($id_laboratorio, $giorno_da_eliminare) {
        return DB::table('calendario_disponibilita')
            ->where('id_laboratorio', $id_laboratorio)
            ->where('giorno_settimana', $giorno_da_eliminare)
            ->delete();
    }
}
