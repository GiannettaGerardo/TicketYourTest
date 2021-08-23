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
        $data = [
            [ 'giorno_settimana' => 'lunedi', 'id_laboratorio' => 4, 'ora_inizio' => 8, 'ora_fine' => 21 ],
            [ 'giorno_settimana' => 'martedi', 'id_laboratorio' => 4, 'ora_inizio' => 8, 'ora_fine' => 21 ],
            [ 'giorno_settimana' => 'giovedi', 'id_laboratorio' => 4, 'ora_inizio' => 8, 'ora_fine' => 21 ],
            [ 'giorno_settimana' => 'venerdi', 'id_laboratorio' => 4, 'ora_inizio' => 8, 'ora_fine' => 21 ],
            [ 'giorno_settimana' => 'sabato', 'id_laboratorio' => 4, 'ora_inizio' => 8, 'ora_fine' => 15 ],
        ];
        DB::table($tableName)->insert($data);
    }
}
