<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioGuardiao extends Model
{
    protected $table = 'usuarios_guardioes';

    protected $fillable = [
        'id_usuaria',
        'id_guardiao'
    ];
}
