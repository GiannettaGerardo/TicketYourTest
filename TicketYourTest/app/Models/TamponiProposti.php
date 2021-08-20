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
     * Metodo per restituire i tamponi proposti da un determinato laboratorio.
     * In particolare vengono restituiti:
     * - la Partita IVA del laboratorio in questione;
     * - l'id del tampone;
     * - il nome del tampone;
     * - la sua descrizione;
     * - il suo costo.
     * @param $partita_iva
     * @return \Illuminate\Support\Collection Il tampone o nulla
     */
    static function getTamponiByLaboratorio($partita_iva) {
        $lab_table = 'laboratorio_analisi';
        $tamp_table = 'tamponi';

        return DB::table('tamponi_proposti')
            ->select('partita_iva', $tamp_table.'.id', $tamp_table.'.nome', 'descrizione', 'costo')
            ->join($lab_table, 'tamponi_proposti.partita_iva_lab', '=', $lab_table.'.partita_iva')  // Join con la tabella laboratorio_analisi
            ->join($tamp_table, 'tamponi_proposti.id_tampone', '=', $tamp_table.'.id')  // Join con la tabella tamponi
            ->where('partita_iva', $partita_iva)
            ->get();
    }

}
