<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PrenotazioniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table_name = 'prenotazioni';
        $data = [
          [
              'data_prenotazione' => '2021-10-22',
              'data_tampone' => '2021-10-23',
              'id_tampone' => '1',
              'cf_prenotante' => 'CTGFNC00B10E716C',
              'email' => 'catignanof@gmail.com',
              'numero_cellulare' => null,
              'id_laboratorio' => '1'
          ]
        ];

        DB::table($table_name)->insert($data);
    }
}
