<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Token_api extends Model
{
    use HasFactory;

    static function tokenEsiste($token)
    {
        return DB::table('token_api')
            ->where('token', $token)
            ->first();
    }
}
