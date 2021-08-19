<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TamponiProposti extends Model
{
    use HasFactory;

    protected $table = 'tamponi_proposti';

    static function getTamponiByLaboratorio($partita_iva) {
        $lab_table = 'laboratorio_analisi';
        $tamp_table = 'tamponi';

        return DB::table(self::$table)
            ->select('partita_iva', $tamp_table.'.id', 'descrizione', 'costo')
            ->join($lab_table, self::$table.'.partita_iva_lab', '=', $lab_table.'.partita_iva')  // Join con la tabella laboratorio_analisi
            ->join($tamp_table, self::$table.'.id_tampone', '=', $tamp_table.'.id')
            ->where('partita_iva', $partita_iva);
    }

}
