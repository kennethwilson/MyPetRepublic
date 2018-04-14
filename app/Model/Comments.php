<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
  protected $table = 'comments';
  protected $fillable = ['user_id','post_id','comment'];
}
