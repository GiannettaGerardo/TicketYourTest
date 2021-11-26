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
                'data_prenotazione' => Carbon::now()->format('Y-m-d'),
                'data_tampone' => Carbon::now()->addDay()->format('Y-m-d'),
                'id_tampone' => '1',
                'cf_prenotante' => 'CTGFNC00B10E716C',
                'email' => 'catignanof@gmail.com',
                'numero_cellulare' => null,
                'id_laboratorio' => '1'
            ],
            [
                'data_prenotazione' => Carbon::now()->format('Y-m-d'),
                'data_tampone' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'id_tampone' => '2',
                'cf_prenotante' => 'RSSMRO65M05A404R',
                'email' => 'mario.rossi@email.com',
                'numero_cellulare' => null,
                'id_laboratorio' => '13'
            ],
            [
                'data_prenotazione' => Carbon::now()->subDay()->format('Y-m-d'),
                'data_tampone' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'id_tampone' => '1',
                'cf_prenotante' => 'VRDLCU93A58I202L',
                'email' => 'lucia.verdi@email.com',
                'numero_cellulare' => null,
                'id_laboratorio' => '11'
            ],
            [
                'data_prenotazione' => date('Y-m-d', strtotime('2021-11-15')),
                'data_tampone' => date('Y-m-d', strtotime('2021-11-16')),
                'id_tampone' => '2',
                'cf_prenotante' => 'CTGFNC00B10E716C',
                'email' => 'catignanof@gmail.com',
                'numero_cellulare' => null,
                'id_laboratorio' => '11'
            ],
            [
                'data_prenotazione' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'data_tampone' => Carbon::now()->subDay()->format('Y-m-d'),
                'id_tampone' => '2',
                'cf_prenotante' => 'VRDLCU93A58I202L',
                'email' => 'lucia.verdi@email.com',
                'numero_cellulare' => null,
                'id_laboratorio' => '11'
            ],
            [
                'data_prenotazione' => Carbon::now()->subDay()->format('Y-m-d'),
                'data_tampone' => Carbon::now()->format('Y-m-d'),
                'id_tampone' => '1',
                'cf_prenotante' => 'RSSMRO65M05A404R',
                'email' => 'mario.rossi@email.com',
                'numero_cellulare' => null,
                'id_laboratorio' => '1'
            ]
        ];

        DB::table($table_name)->insert($data);
    }
}
