<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotaModel extends Model
{
    use HasFactory;

    protected $table = 'tbRota';

    protected $primaryKey = 'id_rota';

    public $fillable = [
        'id_usuaria',
        'origemLatitude',
        'origemLongitude',
        'destinoLatitude',
        'destinoLongitude',
        'tempoPrevisto',   
        'dataCriacao',
    ];

    public $timestamps = false;

    public function pontos()
    {
        return $this->hasMany(pontosRotaModel::class, 'id_rota');
    }

    public function usuaria()
    {
        return $this->belongsTo(UsuariaModel::class, 'id_usuaria');
    }
}
