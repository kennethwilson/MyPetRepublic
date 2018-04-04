<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Followers;
use App\Model\Doggie;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
  protected $doggie;
  protected $user;
  protected $followers;

  public function __construct(User $user, Followers $followers, Doggie $doggie)
  {
    $this->user = $user;
    $this->followers = $followers;
    $this->doggie = $doggie;
  }

  public function all()
  {
      $users = $this->user->all();
      return response()->json($users,200);
  }
  public function getName()
  {
    $query = $this->user->find(auth()->user()->id);
    return response()->json($query->name);
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
        return response()->json(['success'=> false, 'error'=> $ex],422);
      }
    }
      $query = $this->user->find(auth()->user()->id);
      $query->name = $request->name;
      $query->bio = $request->bio;
    try{
      $update =  $query->save();
      return response()->json(['success'=> true, 'message'=> "Successfully updated user profile."]);
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

  public function addDoggie(Request $request)
  {
      $name = $request->name;
      $age = $request ->age;
      $desc = $request ->desc;
      $breed = $request ->breed;

      $doggie = [
        "name"   => $name,
        "age"    => $age,
        "desc"   => $desc,
        "breed"  => $breed,
        "owner_id" => auth()->user()->id
      ];
      try{
        $add= $this->doggie->create($doggie);
          return response()->json(['success'=> true, 'message'=> "Doggie Successfully Added!!"]);
      }
      catch(Exception $ex)
      {
          return response()->json(['success'=> false, 'error'=> $ex]);
      }
  }

  public function viewAllDoggie()
  {
      $doggies  = $this->user->with('doggies')->get();
      return response()->json($doggies,200);
  }

  public function deleteDoggie($doggieID)
  {
    try{
      $doggie = $this->doggie->where('id',$doggieID) ->delete();
        return response()->json(['success'=> true, 'message'=> "Doggie Successfully Deleted!!"]);
    }
    catch(Exception $ex)
    {
      return response()->json(['success'=> false, 'error'=> $ex]);
    }
  }
  public function updateDoggie(Request $request,$doggieID)
  {
    $name  = $request->name;
    $breed = $request->breed;
    $age   = $request->age;
    $desc  = $request->desc;

    $query = $this->doggie->find($doggieID);

    $query->name = $name;
    $query->breed = $breed;
    $query->age = $age;
    $query->desc = $desc;

    try{
      $update =  $query->save();
      return response()->json(['success'=> true, 'message'=> "Successfully updated your doggy's profile."]);
    }
    catch(Exception $ex)
    {
      return response()->json(['success'=> false, 'error'=> $ex]);
    }
  }

}
