<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class alertaModel extends Model
{
    use HasFactory;

    protected $table = 'tbAlerta';

    protected $primaryKey = 'id_alerta';

    public $timestamps = false;

    protected $fillable = [
        'latitude',
        'longitude',
        'statusAlerta',
        'descricao',
        'dataHoraAlerta',
        'id_tipoAlerta',
        'id_usuaria'
    ];
}