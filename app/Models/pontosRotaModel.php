<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pontosRotaModel extends Model
{
    use HasFactory;

    protected $table = 'tbPontosRota';

    protected $primaryKey = 'id_pontosRota';

    public $fillable = [
        'id_rota',
        'latitude',
        'longitude',
    ];

    public $timestamps = false;

    public function rota()
    {
        return $this->belongsTo(RotaModel::class, 'id_rota');
    }
}