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
        return response()->json(['is_followed'=> "true"]);
      }
      else {
        return response()->json(['is_followed'=> "false"]);
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

    public function followedBy($id) //$id profile orngnya // target profile id
    {
      $query = $this->followers->where('follower_id',auth()->user()->id)->pluck('followed_id');  //orng" yg kta follow
      $query2 = $this->followers->where('followed_id',$id)->pluck('follower_id'); //orng" yg follow orng yg kta lgi liat
      $arr = array();
      $count = 0;
      for($i=0;$i<count($query);$i++)
      {
        for($j=0;$j<count($query2);$j++)
        {
          if($query[$i]==$query2[$j])
          {
            $arr[$count]=$query[$i];
            $count =$count +1;
          }
        }
      }
      return $arr;

    }




}
