<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Model\Doggie;
use App\Model\Posts;
use Illuminate\Database\Eloquent\Model;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['password','email','name','is_verified','displaypic','bio'];
    public $timestamps = false;
    public function getJWTIdentifier()
    {
       return $this->getKey();
    }
   public function getJWTCustomClaims()
   {
       return [];
   }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
      public function followers()
      {
        return $this->hasMany(User::class, 'followers', 'followed_id', 'follower_id');
      }

      public function followings()
      {
        return $this->hasMany(User::class, 'followers', 'follower_id', 'followed_id');
      }
      public function doggies()
      {
        return $this->hasMany(Doggie::class,'owner_id','id');
      }

}
