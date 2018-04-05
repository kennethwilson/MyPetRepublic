<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Likes;
class Posts extends Model
{
  protected $table = 'posts';
  protected $fillable = ['pic','caption','location','dog_id'];
    
  public function likes()
  {
    return $this->hasMany(Likes::class,'post_id','id');
  }
}
