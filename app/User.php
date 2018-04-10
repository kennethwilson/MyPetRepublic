<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\Model\Followers;
use App\Model\Doggie;
use App\Model\Posts;
use App\Model\Likes;
use App\Model\Comments;
class User extends Authenticatable
{
    use Notifiable;
    use Searchable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['password','email','username','name','is_verified','displaypic','bio'];

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
      public function toSearchableArray()
      {
          $user = $this->toArray();
          $user['dog'] = $this->doggies->map(function ($data) {
                            return [ 'name'=> $data['name'], 'age'=> $data['age'], 'desc'=> $data['desc'], 'breed'=>$data['breed']];
                          })->toArray();

          unset($user['created_at'], $user['updated_at'],$user['is_verified'],$user['id'],$user['displaypic']);
          return $user;
      }

}
