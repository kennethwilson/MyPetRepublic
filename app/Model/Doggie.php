<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Posts;
class Doggie extends Model
{
      protected $table = 'doggies';
      protected $fillable = ['name','breed','age','desc','owner_id','displaypic'];
      public function posts()
      {
        return $this->hasMany(Posts::class,'dog_id','id');
      }
}
