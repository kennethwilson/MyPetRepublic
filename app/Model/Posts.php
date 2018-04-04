<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
  protected $table = 'posts';
  protected $fillable = ['pic','caption','location','dog_id'];
}
