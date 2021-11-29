<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicoSeeder extends Seeder
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
                'codice_fiscale' => 'CTTFRN50T66G600E',
                'partita_iva' => '99937489293'
            ]
        ];

        DB::table('medico_medicina_generale')->insert($data);
    }
}
