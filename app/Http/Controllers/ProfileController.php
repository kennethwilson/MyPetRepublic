<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Followers;
use App\Notifications\UserFollowed;
use App\Model\Notifications;
class ProfileController extends Controller
{
    protected $user;
    public function __construct(User $user,Followers $followers, UserFollowed $userfollowed, Notifications $notif)
    {
      $this->user = $user;
      $this->followers = $followers;
      $this->UserFollowed = $userfollowed;
      $this->notif = $notif;
    }
    public function is_followed(int $id)
    {
      $userid = auth()->user()->id;
      $query = $this->followers->where([['follower_id','=',$userid],['followed_id','=',$id]])->get();
      if(count($query)!=0)
      {
        return response()->json(['is_followed'=> true]);
      }
      else {
        return response()->json(['is_followed'=> false]);
      }
    }
    public function followUser(int $profileId)
    {
        $user = User::find($profileId);
        if(! $user) {
          return response()->json(['success'=> false, 'error'=> "User does not exist."]);
        }
        // $follow = [
        //   "follower_id"   => auth()->user()->id,
        //   "followed_id"    => $profileId
        // ];
        $follow = new Followers;
        $follow->follower_id = auth()->user()->id;
        $follow->followed_id = $profileId;
        $follow->save();

        $query = $this->user->find($profileId);
        $query->followers = $query->followers + 1;
        $query->save();

        $user->notify(new UserFollowed(auth()->user()));


        return response()->json(['success'=> true, 'message'=> "You are now following user:".$profileId." Notification id: "] );
    }

    public function unFollowUser(int $profileId)
    {
        $user = User::find($profileId);
        if(! $user)
          {
            return response()->json(['success'=> false, 'error'=> "User does not exist."]);
          }

        $query = $this->followers->where([ ['follower_id','=',auth()->user()->id],['followed_id','=',$profileId] ])->delete();
        $query = $this->user->find($profileId);
        $query->followers = $query->followers - 1;
        $query->save();

          return response()->json(['success'=> true, 'message'=> "You are no longer following user: ".$profileId]);
    }
}
