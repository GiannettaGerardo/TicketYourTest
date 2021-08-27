<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CittadinoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cittadino_privato')->insert([
            ['codice_fiscale' => 'CTGFNC00B10E716C'],
            ['codice_fiscale' => 'VRDLCU93A58I202L'],
            ['codice_fiscale' => 'BSCBCM54E50G372U'],
            ['codice_fiscale' => 'PSNYNG59B52F721R'],
            ['codice_fiscale' => 'KLAFLN46R60D918G']
        ]);
    }
}
