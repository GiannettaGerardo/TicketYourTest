<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TamponiProposti extends Model
{
    use HasFactory;

    protected $table = 'tamponi_proposti';


    /**
     * Ritorna la lista di tamponi proposti da uno specifico laboratorio
     * @param $id_laboratorio
     * @return \Illuminate\Support\Collection
     */
    static function getTamponiPropostiByLaboratorio($id_laboratorio) {
        return DB::table('tamponi_proposti')->where('id_laboratorio', $id_laboratorio)->get();
    }


    /**
     * Ritorna i tamponi proposti dai laboratori convenzionati e con calendario disponibilità compilato
     * @return \Illuminate\Support\Collection
     */
    static function getTamponiPropostiLabAttivi() {
        return DB::table('tamponi_proposti')
            ->join('laboratorio_analisi', 'laboratorio_analisi.id', '=', 'tamponi_proposti.id_laboratorio')
            ->where('laboratorio_analisi.convenzionato', '=', 1)
            ->where('laboratorio_analisi.calendario_compilato', '=', 1)
            ->select('tamponi_proposti.id_laboratorio', 'tamponi_proposti.id_tampone', 'tamponi_proposti.costo')
            ->get();
    }


    /**
     * Modifica un tampone nella lista di un laboratorio o ne aggiunge uno nuovo se non già presente
     * @param $id_laboratorio
     * @param $id_tampone
     * @param $costo
     * @return int
     */
    static function upsertListaTamponiOfferti($id_laboratorio, $id_tampone, $costo) {
        return DB::table('tamponi_proposti')->upsert([
            ['id_laboratorio' => $id_laboratorio, 'id_tampone' => $id_tampone, 'costo' => $costo]
        ], ['id_laboratorio', 'id_tampone'], ['costo']);
    }


    /**
     * Elimina un tampone offerto da un laboratorio
     * @param $id_laboratorio
     * @param $id_tampone
     * @return int
     */
    static function deleteTamponeOfferto($id_laboratorio, $id_tampone) {
        return DB::table('tamponi_proposti')
            ->where('id_laboratorio', $id_laboratorio)
            ->where('id_tampone', $id_tampone)
            ->delete();
    }

}
