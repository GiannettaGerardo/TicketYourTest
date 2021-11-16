<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class QuestionarioAnamnesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table_name = 'questionario_anamnesi';
        $data = [
            [
                'id_prenotazione' => 1,
                'cf_paziente' => 'CTGFNC00B10E716C',
                'token' => Str::uuid(),
                'token_scaduto' => 0,
                'motivazione' => null,
                'lavoro' => 0,
                'contatto' => 0,
                'quindici-giorni-dopo-contatto' => 0,
                'tampone-fatto' => 0,
                'isolamento' => 0,
                'contagiato' => 0,
                'febbre' => 0,
                'tosse' => 0,
                'difficolta-respiratorie' => 0,
                'raffreddore' => 0,
                'mal-di-gola' => 0,
                'mancanza-gusto' => 0,
                'dolori-muscolari' => 0,
                'cefalea' => 0
            ],

            [
                'id_prenotazione' => 2,
                'cf_paziente' => 'BSCBCM54E50G372U',
                'token' => Str::uuid(),
                'token_scaduto' => 1,
                'motivazione' => 'lavoro',
                'lavoro' => 1,
                'contatto' => 0,
                'quindici-giorni-dopo-contatto' => 0,
                'tampone-fatto' => 1,
                'isolamento' => 0,
                'contagiato' => 0,
                'febbre' => 0,
                'tosse' => 0,
                'difficolta-respiratorie' => 0,
                'raffreddore' => 0,
                'mal-di-gola' => 0,
                'mancanza-gusto' => 0,
                'dolori-muscolari' => 0,
                'cefalea' => 0
            ],

            [
                'id_prenotazione' => 3,
                'cf_paziente' => 'MSSVLR07R67I930S',
                'token' => Str::uuid(),
                'token_scaduto' => 0,
                'motivazione' => null,
                'lavoro' => 0,
                'contatto' => 0,
                'quindici-giorni-dopo-contatto' => 0,
                'tampone-fatto' => 0,
                'isolamento' => 0,
                'contagiato' => 0,
                'febbre' => 0,
                'tosse' => 0,
                'difficolta-respiratorie' => 0,
                'raffreddore' => 0,
                'mal-di-gola' => 0,
                'mancanza-gusto' => 0,
                'dolori-muscolari' => 0,
                'cefalea' => 0
            ]
        ];

        DB::table($table_name)->insert($data);
    }
}
