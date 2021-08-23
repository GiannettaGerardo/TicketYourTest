<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LaboratorioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tableName = 'laboratorio_analisi';
        $data = [
            [
                'partita_iva' => '86334519757',
                'nome' => 'Laboratorio Fontana',
                'citta' => 'Foggia',
                'provincia' => 'Foggia',
                'indirizzo' => 'Via Miracoli',
                'email' => 'lab.fontana@gmail.com',
                'password' => Hash::make('labfontana')
            ],
            [
                'partita_iva' => '86304509759',
                'nome' => 'Laboratorio S.Spirito',
                'citta' => 'San Severo',
                'provincia' => 'Foggia',
                'indirizzo' => 'Via Ligabue',
                'email' => 's.spirito@gmail.com',
                'password' => Hash::make('labsspirito')
            ],
            [
                'partita_iva' => '86304111119',
                'nome' => 'Laboratorio S.Francesco',
                'citta' => 'San Severo',
                'provincia' => 'Foggia',
                'indirizzo' => 'Via Fortore',
                'email' => 's.francesco@gmail.com',
                'password' => Hash::make('labsfrancesco')
            ],
            [
                'partita_iva' => '11124519757',
                'nome' => 'Laboratorio Croce',
                'citta' => 'Bari',
                'provincia' => 'Bari',
                'indirizzo' => 'Via Gabrieli',
                'coordinata_x' => 41.10643461257548,
                'coordinata_y' => 16.873623977405174,
                'email' => 'lab.croce@gmail.com',
                'password' => Hash::make('laboratoriocroce'),
                'convenzionato' => 1,
                'calendario_compilato' => 1
            ]
        ];
        DB::table($tableName)->insert($data);
    }
}
