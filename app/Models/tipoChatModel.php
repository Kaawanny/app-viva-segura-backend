<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipoChatModel extends Model
{
    use HasFactory;

    protected $table = 'tbTipoChat';

    protected $primaryKey = 'id_tipoChat';

    public $fillable = ['id_tipoChat', 'tipoChat'];

    public $timestamps = false;
}
