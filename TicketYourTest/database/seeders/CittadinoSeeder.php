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
            ['codice_fiscale' => 'VRDLCU93A58I202L']
        ]);
    }
}
