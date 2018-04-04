<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Doggie extends Model
{
      protected $table = 'doggies';
      protected $fillable = ['name','breed','age','desc','owner_id'];
}
