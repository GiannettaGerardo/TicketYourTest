<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            ApiDatoreLavoro::class,
            ApiMedicoMG::class,
            TamponiSeeder::class,
            LaboratorioSeeder::class,
            CalendarioDisponibilita::class,
            UtenteSeeder::class,
            CittadinoSeeder::class,
            MedicoSeeder::class,
            DatoreSeeder::class,
            ListaDipendentiSeeder::class,
            TamponiPropostiSeeder::class,
            PrenotazioniSeeder::class,
            PazientiSeeder::class,
            QuestionarioAnamnesiSeeder::class,
            CarteCreditoSeeder::class,
            Token_api_seeder::class
        ]);
    }
}
