<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{


  protected $user;
  public function __construct(User $user)
  {
    $this->user = $user;
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
        return response('Failed', 401);
      }
    }
      $query = $this->user->find(auth()->user()->id);
      $query->name = $request->name;
      $query->bio = $request->bio;
    try{
      $update =  $query->save();
      return response("Updated",201);
    }
    catch(Exception $ex)
    {
      return $ex;
      return response("Failed",400);
    }
  }



}
