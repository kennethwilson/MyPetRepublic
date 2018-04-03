<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Followers;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{


  protected $user;
  protected $followers;
  public function __construct(User $user, Followers $followers)
  {
    $this->user = $user;
    $this->followers = $followers;
  }

  public function all()
  {
      $users = $this->user->all();
      return response()->json($users,200);
  }
  public function update(Request $request)
  {
    if ($request->hasFile('displaypic')) {
      $file = array('displaypic' => Input::file('displaypic'));
      $destinationPath = 'storage/images'; // upload path
      $extension = Input::file('displaypic')->getClientOriginalExtension();
      $fileName = rand(11111,99999).'.'.$extension; // renaming image
      Input::file('displaypic')->move($destinationPath, $fileName);
      try {
        $query = $this->user->find(auth()->user()->id);
        $original_dp = $query->displaypic;
        $query->displaypic = $fileName;
        $query->save();
        //  echo("<img src='{{ asset($query->displaypic) }}'/>");
        echo "$original_dp";
          if($original_dp != "default.jpg")
          {
            echo("hello");
            Storage::delete('public/images/'.$original_dp);
          }
      }
      catch (\Exception $e) {
        return response()->json(['success'=> false, 'error'=> $ex]);
      }
    }
      $query = $this->user->find(auth()->user()->id);
      $query->name = $request->name;
      $query->bio = $request->bio;
    try{
      $update =  $query->save();
      return response()->json(['success'=> true, 'error'=> "Successfully updated user profile."]);
    }
    catch(Exception $ex)
    {
      return $ex;
      return response()->json(['success'=> false, 'error'=> $ex]);
    }
  }

  public function countFollowings()
  {
    try {
      $id = auth()->user()->id;
      $list = $this->followers->select('followed_id')->where('follower_id','=',$id)->get();
      $count = count($list);
      return response()->json($count,200);
      }
    catch (Exception $e) {
      return response()->json(['success'=> false, 'error'=> $e]);
    }
  }

  public function countFollowers()
  {
    try {
      $id = auth()->user()->id;
      $list = $this->followers->select('follower_id')->where('followed_id','=',$id)->get();
      $count = count($list);
      return response()->json($count,200);
      }
    catch (Exception $e) {
      return response()->json(['success'=> false, 'error'=> $e]);
    }
  }

  public function viewMyFollowings()
  {
    try {
      $id = auth()->user()->id;
      $list = $this->followers->select('followed_id')->where('follower_id','=',$id)->get();
      return response()->json($list,200);
      }
    catch (Exception $e) {
      return response()->json(['success'=> false, 'error'=> $e]);
    }
  }
  public function viewMyFollowers()
  {
    try {
      $id = auth()->user()->id;
      $list = $this->followers->select('follower_id')->where('followed_id','=',$id)->get();
      return response()->json($list,200);
      }
    catch (Exception $e) {
      return response()->json(['success'=> false, 'error'=> $e]);
    }
  }

}
