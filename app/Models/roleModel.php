<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roleModel extends Model
{
    use HasFactory;

    protected $table = 'tbRole';

    public $fillable = ['id','nomeRole1','created_at','updated_at'];

    //public $timestamps = false;
}
