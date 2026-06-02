<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enderecoModel extends Model
{
    use HasFactory;

    protected $table = 'tbEnderecoConfiavel';

    protected $primaryKey = 'id_endereco';

    public $fillable = ['id_endereco', 'nomeLocal', 'longitude', 'latitude', 'raioSeguro', 'logradouro', 'numLogra', 'cep', 'complemento', 'bairro', 'estado', 'dataCriacao', 'id_usuaria'];

    public $timestamps = false;
}
