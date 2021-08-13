<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tableName = 'amministratore';
        $data = [
            [
                'nome' => 'admin-1',
                'email' => 'admin-1@admin.com',
                'password' => Hash::make('admin-1')
            ]
        ];
        DB::table($tableName)->insert($data);
    }
}
