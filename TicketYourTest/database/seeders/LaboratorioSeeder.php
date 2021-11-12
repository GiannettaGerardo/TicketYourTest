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
                'provincia' => 'FG',
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
                'provincia' => 'FG',
                'indirizzo' => 'Via Ligabue',
                'email' => 's.spirito@gmail.com',
                'password' => Hash::make('labsspirito'),
                'coordinata_x' => 41.690176221337914,
                'coordinata_y' => 15.373783138666381,
                'convenzionato' => 1,
                'calendario_compilato' => 1,
                'capienza_giornaliera' => 130
            ],
            [
                'partita_iva' => '86304111119',
                'nome' => 'Laboratorio S.Francesco',
                'citta' => 'San Severo',
                'provincia' => 'FG',
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
                'provincia' => 'BA',
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
                'provincia' => 'FG',
                'indirizzo' => 'Corso Manfredi',
                'email' => 'lab.giga@gmail.com',
                'password' => Hash::make('laboratoriogiga'),
                'coordinata_x' => 41.50618705466236,
                'coordinata_y' => 15.334344528687376,
                'convenzionato' => 1,
                'calendario_compilato' => 0,
                'capienza_giornaliera' => 0
            ],
            [
                'partita_iva' => '88888788710',
                'nome' => 'Laboratorio Italia',
                'citta' => 'Bari',
                'provincia' => 'BA',
                'indirizzo' => 'Viale Unità d\'Italia',
                'email' => 'labitalia@gmail.com',
                'password' => Hash::make('laboratorioitalia'),
                'coordinata_x' => 41.113374784344906,
                'coordinata_y' => 16.873372758639245,
                'convenzionato' => 1,
                'calendario_compilato' => 1,
                'capienza_giornaliera' => 150
            ],
            [
                'partita_iva' => '98888788710',
                'nome' => 'Laboratorio Vittorini',
                'citta' => 'Barletta',
                'provincia' => 'BT',
                'indirizzo' => 'Via Elio Vittorini',
                'email' => 'labvittorini@gmail.com',
                'password' => Hash::make('laboratoriovittorini'),
                'coordinata_x' => 41.31792444282518,
                'coordinata_y' => 16.28333655954337,
                'convenzionato' => 1,
                'calendario_compilato' => 1,
                'capienza_giornaliera' => 90
            ],
            [
                'partita_iva' => '98822788711',
                'nome' => 'Laboratorio Umberto',
                'citta' => 'Trani',
                'provincia' => 'BT',
                'indirizzo' => 'Via Umberto',
                'email' => 'labumberto@gmail.com',
                'password' => Hash::make('laboratorioumberto'),
                'coordinata_x' => 41.27736245768765,
                'coordinata_y' => 16.415372707765215,
                'convenzionato' => 1,
                'calendario_compilato' => 1,
                'capienza_giornaliera' => 110
            ],
            [
                'partita_iva' => '18822788700',
                'nome' => 'Laboratorio Bergamo',
                'citta' => 'Bisceglie',
                'provincia' => 'BT',
                'indirizzo' => 'Via Bergamo',
                'email' => 'labbergamo@gmail.com',
                'password' => Hash::make('laboratoriobergamo'),
                'coordinata_x' => 41.239488873171254,
                'coordinata_y' => 16.507893007117598,
                'convenzionato' => 1,
                'calendario_compilato' => 1,
                'capienza_giornaliera' => 150
            ],
            [
                'partita_iva' => '18822789700',
                'nome' => 'Laboratorio Eritrea',
                'citta' => 'Bari',
                'provincia' => 'BA',
                'indirizzo' => 'Via Eritrea',
                'email' => 'laberitrea@gmail.com',
                'password' => Hash::make('laboratorioeritrea'),
                'coordinata_x' => 41.11882624454362,
                'coordinata_y' => 16.862503104437113,
                'convenzionato' => 1,
                'calendario_compilato' => 1,
                'capienza_giornaliera' => 150
            ],
            [
                'partita_iva' => '78822789701',
                'nome' => 'Laboratorio Ferrara',
                'citta' => 'Napoli',
                'provincia' => 'NA',
                'indirizzo' => 'Via Ferrara',
                'email' => 'labferrara@gmail.com',
                'password' => Hash::make('laboratorioferrara'),
                'coordinata_x' => 40.855798271246485,
                'coordinata_y' => 14.27525950366552,
                'convenzionato' => 1,
                'calendario_compilato' => 1,
                'capienza_giornaliera' => 100
            ],
            [
                'partita_iva' => '08822789702',
                'nome' => 'Laboratorio Roma',
                'citta' => 'Caserta',
                'provincia' => 'CE',
                'indirizzo' => 'Via Roma',
                'email' => 'labroma@gmail.com',
                'password' => Hash::make('laboratorioroma'),
                'coordinata_x' => 41.07007424732212,
                'coordinata_y' => 14.333850168395138,
                'convenzionato' => 1,
                'calendario_compilato' => 1,
                'capienza_giornaliera' => 100
            ],
            [
                'partita_iva' => '03822739702',
                'nome' => 'Laboratorio Zara',
                'citta' => 'Potenza',
                'provincia' => 'PZ',
                'indirizzo' => 'Via Zara',
                'email' => 'labzara@gmail.com',
                'password' => Hash::make('laboratoriozara'),
                'coordinata_x' => 40.642988286351084,
                'coordinata_y' => 15.802598526409623,
                'convenzionato' => 1,
                'calendario_compilato' => 1,
                'capienza_giornaliera' => 70
            ]
        ];
        DB::table($tableName)->insert($data);
    }
}
