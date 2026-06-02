<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnderecoPesquisaModel extends Model
{
     use HasFactory;

    protected $table = 'tb_endereco_pesquisa';

    protected $primaryKey = 'idEnderecoPesquisa';

    public $fillable = ['idEnderecoPesquisa', 'enderecoPesquisa', 'updated_at', 'created_at'];

    public $timestamps = false;
}
