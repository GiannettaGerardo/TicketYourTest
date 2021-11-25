<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefertiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id_prenotazione' => 1,
                'cf_paziente' => 'CTGFNC00B10E716C',
                'esito_tampone' => null,
                'quantita' => null
            ],

            [
                'id_prenotazione' => 2,
                'cf_paziente' => 'BSCBCM54E50G372U',
                'esito_tampone' => null,
                'quantita' => null
            ],

            [
                'id_prenotazione' => 3,
                'cf_paziente' => 'MSSVLR07R67I930S',
                'esito_tampone' => 'positivo',
                'quantita' => 15.3
            ],

            [
                'id_prenotazione' => 4,
                'cf_paziente' => 'VWLPBV95C02F646R',
                'esito_tampone' => 'positivo',
                'quantita' => 17.2
            ]
        ];

        DB::table('referti')->insert($data);
    }
}
