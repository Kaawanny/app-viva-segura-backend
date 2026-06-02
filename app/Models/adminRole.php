<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class adminRole extends Model
{
    use HasFactory;

    protected $table = 'tbAdmin';

    protected $primaryKey = 'id_admin';

    public $fillable = ['id_admin', 'nome', 'cpf', 'email', 'senha', 'dataNasc'];
}
