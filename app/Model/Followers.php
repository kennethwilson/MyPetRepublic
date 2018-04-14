<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Followers extends Model
{
  protected $table = 'followers';
  protected $fillable = ['follower_id','followed_id'];
}
