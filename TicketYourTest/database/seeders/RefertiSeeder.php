<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                'quantita' => null,
                'data_referto' => null
            ],

            [
                'id_prenotazione' => 2,
                'cf_paziente' => 'BSCBCM54E50G372U',
                'esito_tampone' => null,
                'quantita' => null,
                'data_referto' => null
            ],

            [
                'id_prenotazione' => 3,
                'cf_paziente' => 'MSSVLR07R67I930S',
                'esito_tampone' => 'positivo',
                'quantita' => 15.3,
                'data_referto' => Carbon::now()->addDays(4)->format('Y-m-d')
            ],

            [
                'id_prenotazione' => 4,
                'cf_paziente' => 'VWLPBV95C02F646R',
                'esito_tampone' => 'positivo',
                'quantita' => 17.2,
                'data_referto' => Carbon::createFromFormat('Y-m-d', '2021-11-16')->addDay()->format('Y-m-d')
            ],
            [
                'id_prenotazione' => 5,
                'cf_paziente' => 'PLMRCM69R30L274E',
                'esito_tampone' => 'positivo',
                'quantita' => 21.7,
                'data_referto' => Carbon::now()->format('Y-m-d')
            ],
            [
                'id_prenotazione' => 6,
                'cf_paziente' => 'TLNVSC89H19G139V',
                'esito_tampone' => 'negativo',
                'quantita' => null,
                'data_referto' => Carbon::now()->format('Y-m-d')
            ],
            [
                'id_prenotazione' => 7,
                'cf_paziente' => 'PLMRCM69R30L274E',
                'esito_tampone' => 'positivo',
                'quantita' => 16.7,
                'data_referto' => Carbon::create(2021, 11)->format('Y-m-d')
            ],
        ];

        DB::table('referti')->insert($data);
    }
}
