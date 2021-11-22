<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CartaCredito extends Model
{
    use HasFactory;


    /**
     * Controlla l'esistenza di una carta di credito.
     * @param $nome_proprietario
     * @param $numero_carta
     * @param $exp
     * @param $cvv
     * @return bool
     */
    static function existsCartaCredito($nome_proprietario, $numero_carta, $exp, $cvv) {
        $carta = DB::table('carte_credito')
            ->where('nome_proprietario', '=', $nome_proprietario)
            ->where('numero', '=', $numero_carta)
            ->where('exp', '=', $exp)
            ->where('cvv', '=', $cvv)
            ->first();

        return isset($carta);
    }
}
