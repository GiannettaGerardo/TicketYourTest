<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TamponiPropostiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tableName = 'tamponi_proposti';
        $data = [
            [
                'id_laboratorio' => 1,
                'id_tampone' => 1,
                'costo' => 10.00
            ],
            [
                'id_laboratorio' => 1,
                'id_tampone' => 2,
                'costo' => 14.90
            ],
            [
                'id_laboratorio' => 5,
                'id_tampone' => 1,
                'costo' => 9.99
            ]
        ];
        DB::table($tableName)->insert($data);
    }
}
