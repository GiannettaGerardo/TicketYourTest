<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApiMediciItaliani extends Model
{
    use HasFactory;

    static function esistePartitaIvaMedico($partita_iva)
    {
        return DB::table('api_medici_italiani')
            ->where('partita_iva', $partita_iva)->first();
    }
}
