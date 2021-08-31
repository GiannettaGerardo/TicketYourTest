<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CalendarioDisponibilita extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tableName = 'calendario_disponibilita';
        $ore_8_am = date('H:i:s', strtotime('08:00:00'));
        $ore_21_am = date('H:i:s', strtotime('21:00:00'));
        $ore_15_am = date('H:i:s', strtotime('15:00:00'));
        $data = [
            [ 'giorno_settimana' => 'lunedi', 'id_laboratorio' => 4, 'oraApertura' => $ore_8_am, 'oraChiusura' => $ore_21_am ],
            [ 'giorno_settimana' => 'martedi', 'id_laboratorio' => 4, 'oraApertura' => $ore_8_am, 'oraChiusura' => $ore_21_am ],
            [ 'giorno_settimana' => 'giovedi', 'id_laboratorio' => 4, 'oraApertura' => $ore_8_am, 'oraChiusura' => $ore_21_am ],
            [ 'giorno_settimana' => 'venerdi', 'id_laboratorio' => 4, 'oraApertura' => $ore_8_am, 'oraChiusura' => $ore_21_am ],
            [ 'giorno_settimana' => 'sabato', 'id_laboratorio' => 4, 'oraApertura' => $ore_8_am, 'oraChiusura' => $ore_15_am ],
        ];
        DB::table($tableName)->insert($data);
    }
}
