<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Likes;
use App\Model\Comments;
class Posts extends Model
{
  protected $table = 'posts';
  protected $fillable = ['pic','caption','location','dog_id'];

  public function likes()
  {
    return $this->hasMany(Likes::class,'post_id','id');
  }
  public function comments()
  {
    return $this->hasMany(Comments::class,'post_id','id');
  }
}
