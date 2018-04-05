<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Followers;
class ProfileController extends Controller
{
    protected $user;
    public function __construct(User $user,Followers $followers)
    {
      $this->user = $user;
      $this->followers = $followers;
    }

    public function followUser(int $profileId)
    {
        $user = User::find($profileId);
        if(! $user) {
          return response()->json(['success'=> false, 'error'=> "User does not exist."]);
        }
        $follow = [
          "follower_id"   => auth()->user()->id,
          "followed_id"    => $profileId
        ];
        $add= $this->followers->create($follow);
        return response()->json(['success'=> true, 'message'=> "Successfully followed the user."]);
    }

    public function unFollowUser(int $profileId)
    {
        $user = User::find($profileId);
        if(! $user)
          {
            return response()->json(['success'=> false, 'error'=> "User does not exist."]);
          }
        $query = $this->followers->where([ ['follower_id','=',auth()->user()->id],['followed_id','=',$profileId] ])->delete();
          return response()->json(['success'=> true, 'message'=> "Successfully unfollowed the user."]);
    }
}
