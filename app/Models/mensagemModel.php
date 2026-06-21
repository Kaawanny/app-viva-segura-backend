<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model {
    use HasFactory;

    protected $table = 'tb_mensagem';

    protected $fillable = [
        'texto',
        'usuario_id',
        'guardiao_id',
        'enviado_por'
    ];
}