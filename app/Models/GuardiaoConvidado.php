<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuardiaoConvidado extends Model
{
    protected $table = 'guardioes_convidados';

    protected $fillable = [
        'id_usuaria',
        'nome',
        'email'
    ];
}