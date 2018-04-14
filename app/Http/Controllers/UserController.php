<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Followers;
use App\Model\Doggie;
use App\Model\Posts;
use App\Model\Likes;
use App\Model\Comments;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Input;


class UserController extends Controller
{
  protected $doggie;
  protected $user;
  protected $followers;
  protected $posts;
  public function __construct(User $user, Followers $followers, Doggie $doggie, Posts $posts, Likes $likes, Comments $comments)
  {
    $this->user = $user;
    $this->followers = $followers;
    $this->doggie = $doggie;
    $this->posts = $posts;
    $this->likes = $likes;
    $this->comments = $comments;
  }

  public function all()
  {
      $users = $this->user->all();
      return response()->json($users,200);
  }
  public function getName()
  {
    return response()->json(["name"=>auth()->user()->name]);
  }
  public function update(Request $request)
  {
    if ($request->hasFile('displaypic')) {
      $destinationPath = 'storage/images'; // upload path
      $extension = Input::file('displaypic')->getClientOriginalExtension();
      $fileName = rand(11111,99999).".".$extension; // renaming image
      Input::file('displaypic')->move($destinationPath, $fileName);

      //Storage::disk('spaces')->putFile($folder, $request->displaypic,'public');
      try {
        $query = $this->user->find(auth()->user()->id);
        $original_dp = $query->displaypic;
        $query->displaypic = $fileName;
        $query->save();
        if($original_dp!= 'default.jpg')
        {
          Storage::delete('public/images/'.$original_dp);
        }
      }
      catch (Exception $e) {
        return response()->json(['success'=> false, 'error'=> $e],422);
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
      $gender = $request->gender;
      // $doggie = [
      //   "name"   => $name,
      //   "age"    => $age,
      //   "desc"   => $desc,
      //   "breed"  => $breed,
      //   "owner_id" => auth()->user()->id
      // ];

      $doggie = new Doggie;
      $doggie->name = $name;
      $doggie->age = $age;
      $doggie->desc = $desc;
      $doggie->breed = $breed;
      $doggie->gender = $gender;
      $doggie->owner_id = auth()->user()->id;

      if ($request->hasFile('displaypic')) {
        $destinationPath = 'storage/images'; // upload path
        $extension = Input::file('displaypic')->getClientOriginalExtension();
        $fileName = rand(11111,99999).".".$extension; // renaming image
        Input::file('displaypic')->move($destinationPath, $fileName);

        $doggie->displaypic = $fileName;
        try {
          $doggie->save();
          return response()->json(['success'=> true, 'message'=> "Doggie Successfully Added!!"]);
        }
        catch (Exception $e) {
          return response()->json(['success'=> false, 'error'=> $e],422);
        }
      }
      else {
        try {
          $doggie->save();
          return response()->json(['success'=> true, 'message'=> "Doggie Successfully Added!!"]);
        }
        catch (Exception $e) {
          return response()->json(['success'=> false, 'error'=> $e],422);
        }
      }
  }

  public function viewAllDoggie()
  {
      $doggies  = $this->user->with('doggies')->get();
      return response()->json($doggies,200);
  }
  public function viewAllMyDoggie()
  {
    $doggies = $this->user->with('doggies')->find(auth()->user()->id);
    return response()->json($doggies);
  }
  public function deleteDoggie($doggieID)
  {
    try{
      $doggie = $this->doggie->find($doggieID);
      Storage::delete('public/images/'.$doggie->displaypic);
      $delete= $this->doggie->where('id',$doggieID)->delete();
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

    if ($request->hasFile('displaypic')) {
      $destinationPath = 'storage/images'; // upload path
      $extension = Input::file('displaypic')->getClientOriginalExtension();
      $fileName = rand(11111,99999).".".$extension; // renaming image
      Input::file('displaypic')->move($destinationPath, $fileName);

      //Storage::disk('spaces')->putFile($folder, $request->displaypic,'public');
      try {
        $query = $this->doggie->find($doggieID);
        $original_dp = $query->displaypic;
        $query->displaypic = $fileName;
        $query->save();
        if($original_dp!= 'default2.jpg')
        {
          Storage::delete('public/images/'.$original_dp);
        }
      }
      catch (Exception $e) {
        return response()->json(['success'=> false, 'error'=> $e],422);
      }
    }


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

  public function likePost($post_id)
  {
    // $like = [
    //   "user_id"   => auth()->user()->id,
    //   "post_id"    => $post_id
    // ];
    $like = new Likes;
    $like->user_id = auth()->user()->id;
    $like->post_id = $post_id;
    $like->save();
    return response()->json(['success'=> true, 'message'=> "Successfully liked the post!!"]);
  }

  public function unlikePost($post_id)
  {
      $query = $this->likes->where([ ['user_id','=',auth()->user()->id],['post_id','=',$post_id] ])->delete();
      return response()->json(['success'=> true, 'message'=> "Successfully unliked the post."]);
  }

  public function post_is_liked($post_id)  //to check if user has already liked post
  {
      $query = $this->likes->where([ ['user_id','=',auth()->user()->id],['post_id','=',$post_id] ])->get();
      if(count($query)!=0)
      {
        return response()->json(['is_post_liked?'=> 'yes']);
      }
      else {
        return response()->json(['is_post_liked'=>'no']);
      }
  }

  public function comment_post(Request $request, $post_id)
  {
      $comment = $request->comment;
      // if($comment=="")               NOT NEEDED
      // {                              soalnya di frontend bsa validate kalo textbox kosong gbsa send commentnya
      //   return response()->json(['success'=> false, 'error'=> 'Comment is empty']);
      // }
      // $comment = [                                 mass assignment
      //   "user_id"  => auth()->user()->id,          gabsa synchronize sma algolia
      //   "post_id"  => $post_id,
      //   'comment'  => $comment
      // ];
      //jdi hrus:
      $comments = new Comments;
      $comments->user_id = auth()->user()->id;
      $comments->post_id = $post_id;
      $comments->comment = $comment;

      try {
        $comments->save();
        return response()->json(['success'=> true, 'message'=> "Comment successfully sent!!"]);
      } catch (\Exception $e) {
        return response()->json(['success'=> false, 'error'=> $e]);
      }
  }
      public function delete_comment($comment_id)
      {
        try {
          $query = $this->comments->where('id',"=",$comment_id)->first();
          $post = $query->post_id;
          $search_post = $this->posts->where('id',"=",$post)->first();
          $dog = $search_post->dog_id;

          $search_user = $this->doggie->where('id',"=",$dog)->first();
          $user = $search_user->owner_id;

          if($query->user_id == auth()->user()->id)    //check if it is the user's comment
          {
            $del =  $this->comments->where('id',"=",$comment_id)->delete();
            return response()->json(['success'=> true, 'message'=> "Comment Successfully Deleted!!"]);
          }
          elseif($user == auth()->user()->id) //check if it is the user's post (in this case, user can delete all its posts' comments)
          {
            $query = $this->comments->where('id',"=",$comment_id)->delete();
            return response()->json(['success'=> true, 'message'=> "Comment Successfully Deleted!!"]);
          }

          else {
            return response()->json(['success'=> false, 'error'=> "You cannot delete this comment"]);
          }

        } catch (\Exception $e) {
            return response()->json(['success'=> false, 'error'=> $e]);
        }

      }
}
