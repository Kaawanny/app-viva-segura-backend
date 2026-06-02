<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class localModel extends Model
{
    use HasFactory;

    protected $table = 'tbLocalTempoReal';

    protected $primaryKey = 'id_localTempoReal';

    protected $fillable = [
        'latitude',
        'longitude',
        'id_usuaria'
    ];
}