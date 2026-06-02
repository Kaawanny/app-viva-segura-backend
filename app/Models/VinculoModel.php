<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VinculoModel extends Model
{
    use HasFactory;

    protected $table = 'tbVinculo';

    protected $fillable = [
        'id_usuaria',
        'id_guardiao',
        'dataSolicitacao',
        'dataResposta',
        'statusVinculo'
    ];

    public function usuaria()
    {
        return $this->belongsTo(UsuariaModel::class, 'id_usuaria', 'id_usuaria');
    }

    public function guardiao()
    {
        return $this->belongsTo(UsuariaModel::class, 'id_guardiao', 'id_usuaria');
    }
}
