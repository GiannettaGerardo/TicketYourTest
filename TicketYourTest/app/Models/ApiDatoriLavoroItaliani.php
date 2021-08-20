<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApiDatoriLavoroItaliani extends Model
{
    use HasFactory;

    static function esistePartitaIvaDatore($partita_iva)
    {
        return DB::table('api_datori_lavoro_italiani')
            ->where('partita_iva', $partita_iva)->first();
    }

}
