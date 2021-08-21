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
     * Inserisce un nuovo tampone offerto da un determinato laboratorio.
     * @param $partita_iva_lab La partita iva del laboratorio.
     * @param $id_tampone L'id del tampone proposto,
     * @param $costo Il costo de tampone proposto.
     * @return bool Se l'inserimento e' avvenuto correttamente o meno.
     */
    static function insertNewTamponeProposto($partita_iva_lab, $id_tampone, $costo) {
        return DB::table('tamponi_proposti')->insert([
            'partita_iva_lab' => $partita_iva_lab,
            'id_tampone' => $id_tampone,
            'costo' => $costo
        ]);
    }

    /**
     * Ritorna la lista di tamponi proposti da uno specifico laboratorio
     * @param $id_laboratorio
     * @return \Illuminate\Support\Collection
     */
    static function getTamponiPropostiByLaboratorio($id_laboratorio) {
        return DB::table('tamponi_proposti')->where('id_laboratorio', $id_laboratorio)->get();
    }

    /**
     * Modifica un tampone nella lista di un laboratorio o ne aggiunge uno nuovo se non già presente
     * @param $id_laboratorio
     * @param $id_tampone
     * @param $costo
     */
    static function updateListaTamponiOfferti($id_laboratorio, $id_tampone, $costo) {
        DB::table('tamponi_proposti')->upsert([
            ['id_laboratorio' => $id_laboratorio, 'id_tampone' => $id_tampone, 'costo' => $costo]
        ], ['id_laboratorio', 'id_tampone'], ['costo']);
    }

}
