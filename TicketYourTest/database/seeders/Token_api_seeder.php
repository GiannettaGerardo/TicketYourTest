<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Token_api_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table_name = 'token_api';

        $data = [
            [
                'token' => 'A8ilop0mKKiuUHbg30Kl',
                'descrizione' => 'ASL della provincia di Bari',
                'data_creazione' => date('Y-m-d', strtotime('2021-11-10'))
            ],
            [
                'token' => 'B908H7J6fg54ERbZZMli',
                'descrizione' => 'ASL della provincia di Foggia',
                'data_creazione' => date('Y-m-d', strtotime('2021-10-21'))
            ]
        ];

        DB::table($table_name)->insert($data);
    }
}
