<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rotaCompartilhadaModel extends Model
{
    protected $table = 'tb_rota_compartilhada';
    protected $fillable = [
        'id_usuaria',
        'id_guardiao',
        'origemLatitude',
        'origemLongitude',
        'destinoLatitude',
        'destinoLongitude',
        'endereco_destino',
        'status',
    ];
}
