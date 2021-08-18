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
        DB::table('tamponi')->insert([
            'nome' => 'Tampone rapido',
            'descrizione' => 'Il test rapido antigenico viene eseguito su un campione prelevato tramite tampone naso-faringeo. Esso ricerca alcune componenti proteiche del virus nei campioni prelevati mediante tampone. I tempi di risposta sono molto brevi, circa 5-30 minuti a seconda dello specifico kit utilizzato.'
        ]);
    }
}
