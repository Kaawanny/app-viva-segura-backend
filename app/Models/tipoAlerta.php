<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipoAlerta extends Model
{
    use HasFactory;

    protected $table = 'tbTipoAlerta';

    protected $primaryKey = 'id_tipoAlerta';

    public $fillable = ['id_tipoAlerta', 'tipoAlerta'];

    public $timestamps = false;
}
