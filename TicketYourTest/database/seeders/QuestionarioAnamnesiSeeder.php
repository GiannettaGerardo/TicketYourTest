<?php

namespace Database\Seeders;

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
                'motivazione' => null
            ]
        ];

        DB::table($table_name)->insert($data);
    }
}
