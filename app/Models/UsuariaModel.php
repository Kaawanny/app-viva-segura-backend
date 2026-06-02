<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuariaModel extends Model
{
    use HasFactory;

    protected $table = 'tbUsuaria';

    protected $primaryKey = 'id_usuaria';

protected $fillable = [
    'id_usuaria',
    'nome',
    'cpf',
    'email',
    'senha',
    'dataNasc',
    'telefone',
    'id_role',
    'foto',
    'codigo_convite'
];
}
