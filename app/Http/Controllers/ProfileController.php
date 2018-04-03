<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Followers;
class ProfileController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
      $this->user = $user;
    }

    public function followUser(int $profileId)
    {
        $user = User::find($profileId);
        if(! $user) {
          //return redirect()->back()->with('error', 'User does not exist.');
          return "User does not exist";
        }
        $user->followers()->attach(auth()->user()->id);
        //return redirect()->back()->with('success', 'Successfully followed the user.');
        return 'Successfully followed the user';
    }

    public function unFollowUser(int $profileId)
    {
        $user = User::find($profileId);
        if(! $user)
          {
            //return redirect()->back()->with('error', 'User does not exist.');
            return "User does not exist.";
          }
        $user->followers()->detach(auth()->user()->id);
          //return redirect()->back()->with('success', 'Successfully unfollowed the user.');
          return "Successfully unfollowed the user.";
    }
}
