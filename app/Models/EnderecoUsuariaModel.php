<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnderecoUsuariaModel extends Model
{
  use HasFactory;

    protected $table = 'tb_endereco_usuaria';

    protected $primaryKey = 'idEnderecoUsuaria';

    public $fillable = ['idEnderecoUsuaria', 'enderecoUsuaria','complementoEnderecoUsuaria','descricaoEnderecoUsuaria'];
}
