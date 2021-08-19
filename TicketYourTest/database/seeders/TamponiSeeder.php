<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TamponiSeeder extends Seeder
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
                'nome' => 'Tampone rapido',
                'descrizione' => 'Il test rapido antigenico viene eseguito su un campione prelevato tramite tampone naso-faringeo. Esso ricerca alcune componenti proteiche del virus nei campioni prelevati mediante tampone. I tempi di risposta sono molto brevi, circa 5-30 minuti a seconda dello specifico kit utilizzato.'
            ],
            [
                'nome' => 'Tampone molecolare',
                'descrizione' => 'Il principale e più affidabile strumento diagnostico è il cosiddetto tampone molecolare naso orofaringeo che consiste in un’indagine capace di rilevare il genoma (RNA) del virus SARS-Cov-2 nel campione biologico. L’esito di questo tampone si ottiene mediamente in tre/sei ore.'
            ]

        ];
        DB::table('tamponi')->insert($data);
    }
}
