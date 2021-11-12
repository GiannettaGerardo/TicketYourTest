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
            ],
            [
                'id_laboratorio' => 6,
                'id_tampone' => 1,
                'costo' => 9.99
            ],
            [
                'id_laboratorio' => 2,
                'id_tampone' => 2,
                'costo' => 14.99
            ],
            [
                'id_laboratorio' => 7,
                'id_tampone' => 1,
                'costo' => 9.99
            ],
            [
                'id_laboratorio' => 7,
                'id_tampone' => 2,
                'costo' => 12.99
            ],
            [
                'id_laboratorio' => 8,
                'id_tampone' => 1,
                'costo' => 10.99
            ],
            [
                'id_laboratorio' => 9,
                'id_tampone' => 1,
                'costo' => 9.99
            ],
            [
                'id_laboratorio' => 9,
                'id_tampone' => 2,
                'costo' => 13.99
            ],
            [
                'id_laboratorio' => 10,
                'id_tampone' => 1,
                'costo' => 10.99
            ],
            [
                'id_laboratorio' => 10,
                'id_tampone' => 2,
                'costo' => 15.99
            ],
            [
                'id_laboratorio' => 11,
                'id_tampone' => 1,
                'costo' => 10.99
            ],
            [
                'id_laboratorio' => 11,
                'id_tampone' => 2,
                'costo' => 14.99
            ],
            [
                'id_laboratorio' => 12,
                'id_tampone' => 1,
                'costo' => 8.50
            ],
            [
                'id_laboratorio' => 12,
                'id_tampone' => 2,
                'costo' => 14.50
            ],
            [
                'id_laboratorio' => 13,
                'id_tampone' => 2,
                'costo' => 15.00
            ]
        ];
        DB::table($tableName)->insert($data);
    }
}
