<?php

namespace Database\Seeders;

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
              'data_prenotazione' => date('10-22-2021'),
              'data_tampone' => date('10-23-2021'),
              'id_tampone' => 1,
              'cf_prenotante' => 'CTGFNC00B10E716C',
              'email' => 'catignanof@gmail.com',
              'numero_cellulare' => null,
              'id_laboratorio' => 1
          ]
        ];

        DB::table($table_name)->insert($data);
    }
}
