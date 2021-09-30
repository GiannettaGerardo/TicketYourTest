<?php

namespace App\Models;

use App\Http\Controllers\Attore;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class User
 * Model della tabella del database users
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // QUERY

    /**
     * Ritorna un utente cercandolo per email nel database
     * @param $email
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    static function getByEmail($email) {
        return DB::table('users')->where('email', $email)->first();
    }

    /**
     * Ritorna un utente cercandolo per id nel database
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    static function getById($id) {
        return DB::table('users')->find($id);
    }

    /**
     * Inserisce un nuovo utente nella tabella users del database
     * @param $cod_fiscale    // codice fiscale
     * @param $nome           // nome dell'utente
     * @param $cognome        // cognome dell'utente
     * @param $citta_res      // città di residenza
     * @param $provincia_res  // provincia di residenza
     * @param $email          // email dell'account
     * @param $password       // password dell'account
     * @return bool
     */
    static function insertNewUtenteRegistrato($cod_fiscale, $nome, $cognome, $citta_res, $provincia_res, $email, $password, $attore) {
        return DB::table('users')->insert([
            'codice_fiscale' => $cod_fiscale,
            'nome' => $nome,
            'cognome' => $cognome,
            'citta_residenza' => $citta_res,
            'provincia_residenza' => $provincia_res,
            'email' => $email,
            'password' => Hash::make($password),
            'attore' => $attore
        ]);
    }

    /**
     * Aggiorna i dati di un utente nel database
     * @param $id             // identificativo univoco dell'utente
     * @param $cod_fiscale    // codice fiscale
     * @param $nome           // nome dell'utente
     * @param $cognome        // cognome dell'utente
     * @param $citta_res      // città di residenza
     * @param $provincia_res  // provincia di residenza
     * @param $email          // email dell'account
     * @param $password       // password dell'account
     * @return int
     */
    static function updateInfo($id, $cod_fiscale, $nome, $cognome, $citta_res, $provincia_res, $email)
    {
        return DB::table('users')->where('id', $id)
            ->update([
                'codice_fiscale' => $cod_fiscale,
                'nome' => $nome,
                'cognome' => $cognome,
                'citta_residenza' => $citta_res,
                'provincia_residenza' => $provincia_res,
                'email' => $email
            ]);
    }
}
