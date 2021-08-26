<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UtenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table_name = 'users';

        $data = [
            [
                'codice_fiscale' => 'CTGFNC00B10E716C',
                'nome' => 'Francesco',
                'cognome' => 'Catignano',
                'citta_residenza' => 'Lucera',
                'provincia_residenza' => 'Foggia',
                'email' => 'catignanof@gmail.com',
                'password' => Hash::make('francescocatignano'),
                'is_registrato' => '1'
            ],

            [
                'codice_fiscale' => 'RSSMRO65M05A404R',
                'nome' => 'Mario',
                'cognome' => 'Rossi',
                'citta_residenza' => 'Milano',
                'provincia_residenza' => 'Milano',
                'email' => 'mario.rossi@email.com',
                'password' => Hash::make('mariorossi'),
                'is_registrato' => '1'
            ],

            [
                'codice_fiscale' => 'VRDLCU93A58I202L',
                'nome' => 'Lucia',
                'cognome' => 'Verdi',
                'citta_residenza' => 'Bari',
                'provincia_residenza' => 'Bari',
                'email' => 'lucia.verdi@email.com',
                'password' => Hash::make('luciaverdi'),
                'is_registrato' => '1'
            ],

            [
                'codice_fiscale' => 'BNCCLR81F50H121R',
                'nome' => 'Carla',
                'cognome' => 'Bianchi',
                'citta_residenza' => 'Roma',
                'provincia_residenza' => 'Roma',
                'email' => 'carla.bianchi@email.com',
                'password' => Hash::make('carlabianchi'),
                'is_registrato' => '1'
            ]
        ];

        DB::table($table_name)->insert($data);
    }
}
