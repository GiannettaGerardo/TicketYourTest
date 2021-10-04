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
                'citta' => 'Lucera',
                'provincia' => 'Foggia',
                'indirizzo' => 'Via Iesi, 4',
                'email' => 'lab.fontana@gmail.com',
                'password' => Hash::make('labfontana'),
                'coordinata_x' => '41.5069306',
                'coordinata_y' => '15.3313728',
                'convenzionato' => 1,
                'calendario_compilato' => 1,
                'capienza_giornaliera' => 100
            ],
            [
                'partita_iva' => '86304509759',
                'nome' => 'Laboratorio S.Spirito',
                'citta' => 'San Severo',
                'provincia' => 'Foggia',
                'indirizzo' => 'Via Ligabue',
                'email' => 's.spirito@gmail.com',
                'password' => Hash::make('labsspirito'),
                'coordinata_x' => null,
                'coordinata_y' => null,
                'convenzionato' => 0,
                'calendario_compilato' => 0,
                'capienza_giornaliera' => 0
            ],
            [
                'partita_iva' => '86304111119',
                'nome' => 'Laboratorio S.Francesco',
                'citta' => 'San Severo',
                'provincia' => 'Foggia',
                'indirizzo' => 'Via Fortore',
                'email' => 's.francesco@gmail.com',
                'password' => Hash::make('labsfrancesco'),
                'coordinata_x' => null,
                'coordinata_y' => null,
                'convenzionato' => 0,
                'calendario_compilato' => 0,
                'capienza_giornaliera' => 0
            ],
            [
                'partita_iva' => '11124519757',
                'nome' => 'Laboratorio Croce',
                'citta' => 'Bari',
                'provincia' => 'Bari',
                'indirizzo' => 'Via Gabrieli',
                'email' => 'lab.croce@gmail.com',
                'password' => Hash::make('laboratoriocroce'),
                'coordinata_x' => null,
                'coordinata_y' => null,
                'convenzionato' => 0,
                'calendario_compilato' => 0,
                'capienza_giornaliera' => 0
            ],
            [
                'partita_iva' => '01104510751',
                'nome' => 'Laboratorio Giga',
                'citta' => 'Lucera',
                'provincia' => 'Foggia',
                'indirizzo' => 'Corso Manfredi',
                'email' => 'lab.giga@gmail.com',
                'password' => Hash::make('laboratoriogiga'),
                'coordinata_x' => 41.50618705466236,
                'coordinata_y' => 15.334344528687376,
                'convenzionato' => 1,
                'calendario_compilato' => 0,
                'capienza_giornaliera' => 0
            ]
        ];
        DB::table($tableName)->insert($data);
    }
}
