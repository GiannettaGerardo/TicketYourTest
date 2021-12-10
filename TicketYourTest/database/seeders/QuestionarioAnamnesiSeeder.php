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
                'cefalea' => 0,
                'email_medico' => null
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
                'cefalea' => 0,
                'email_medico' => 'floriana.cattaneo@email.com'
            ],
            [
                'id_prenotazione' => 3,
                'cf_paziente' => 'MSSVLR07R67I930S',
                'token' => Str::uuid(),
                'token_scaduto' => 0,
                'motivazione' => 'lavoro',
                'lavoro' => 1,
                'contatto' => 0,
                'quindici-giorni-dopo-contatto' => 0,
                'tampone-fatto' => 1,
                'isolamento' => 0,
                'contagiato' => 0,
                'febbre' => 0,
                'tosse' => 1,
                'difficolta-respiratorie' => 0,
                'raffreddore' => 0,
                'mal-di-gola' => 1,
                'mancanza-gusto' => 0,
                'dolori-muscolari' => 0,
                'cefalea' => 0,
                'email_medico' => 'floriana.cattaneo@email.com'
            ],
            [
                'id_prenotazione' => 4,
                'cf_paziente' => 'VWLPBV95C02F646R',
                'token' => Str::uuid(),
                'token_scaduto' => 0,
                'motivazione' => 'lavoro',
                'lavoro' => 1,
                'contatto' => 0,
                'quindici-giorni-dopo-contatto' => 0,
                'tampone-fatto' => 1,
                'isolamento' => 0,
                'contagiato' => 0,
                'febbre' => 0,
                'tosse' => 1,
                'difficolta-respiratorie' => 1,
                'raffreddore' => 1,
                'mal-di-gola' => 1,
                'mancanza-gusto' => 1,
                'dolori-muscolari' => 0,
                'cefalea' => 0,
                'email_medico' => 'floriana.cattaneo@email.com'
            ],
            [
                'id_prenotazione' => 5,
                'cf_paziente' => 'PLMRCM69R30L274E',
                'token' => Str::uuid(),
                'token_scaduto' => 0,
                'motivazione' => 'sintomi',
                'lavoro' => 1,
                'contatto' => 1,
                'quindici-giorni-dopo-contatto' => 0,
                'tampone-fatto' => 0,
                'isolamento' => 0,
                'contagiato' => 0,
                'febbre' => 1,
                'tosse' => 1,
                'difficolta-respiratorie' => 1,
                'raffreddore' => 1,
                'mal-di-gola' => 1,
                'mancanza-gusto' => 1,
                'dolori-muscolari' => 0,
                'cefalea' => 0,
                'email_medico' => 'floriana.cattaneo@email.com'
            ],
            [
                'id_prenotazione' => 6,
                'cf_paziente' => 'TLNVSC89H19G139V',
                'token' => Str::uuid(),
                'token_scaduto' => 0,
                'motivazione' => 'lavoro',
                'lavoro' => 1,
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
                'cefalea' => 0,
                'email_medico' => 'floriana.cattaneo@email.com'
            ],
            [
                'id_prenotazione' => 7,
                'cf_paziente' => 'PLMRCM69R30L274E',
                'token' => Str::uuid(),
                'token_scaduto' => 0,
                'motivazione' => 'sintomi',
                'lavoro' => 1,
                'contatto' => 1,
                'quindici-giorni-dopo-contatto' => 0,
                'tampone-fatto' => 0,
                'isolamento' => 0,
                'contagiato' => 0,
                'febbre' => 1,
                'tosse' => 1,
                'difficolta-respiratorie' => 1,
                'raffreddore' => 1,
                'mal-di-gola' => 1,
                'mancanza-gusto' => 1,
                'dolori-muscolari' => 0,
                'cefalea' => 0,
                'email_medico' => 'floriana.cattaneo@email.com'
            ],
            [
                'id_prenotazione' => 8,
                'cf_paziente' => 'VRDLCU93A58I202L',
                'token' => Str::uuid(),
                'token_scaduto' => 0,
                'motivazione' => 'sintomi',
                'lavoro' => 1,
                'contatto' => 1,
                'quindici-giorni-dopo-contatto' => 0,
                'tampone-fatto' => 1,
                'isolamento' => 0,
                'contagiato' => 0,
                'febbre' => 1,
                'tosse' => 1,
                'difficolta-respiratorie' => 1,
                'raffreddore' => 1,
                'mal-di-gola' => 1,
                'mancanza-gusto' => 1,
                'dolori-muscolari' => 0,
                'cefalea' => 0,
                'email_medico' => 'floriana.cattaneo@email.com'
            ]
        ];

        DB::table($table_name)->insert($data);
    }
}
