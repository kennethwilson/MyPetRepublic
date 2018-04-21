<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Followers;
use App\Model\Doggie;
use App\Model\Posts;
use App\Model\Likes;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Input;
class PostsController extends Controller
{
  protected $doggie;
  protected $user;
  protected $followers;
  protected $posts;
  public function __construct(User $user, Followers $followers, Doggie $doggie, Posts $posts, Likes $likes)
  {
    $this->user = $user;
    $this->followers = $followers;
    $this->doggie = $doggie;
    $this->posts = $posts;
    $this->likes  = $likes;
  }
  public function post(Request $request,$dog_id)
  {
      $caption = $request->caption;
      $location = $request ->location;
      if($caption == "undefined")
      {
        $caption=null;
      }
      if($location == "undefined")
      {
        $location=null;
      }


    if ($request->hasFile('pic')) {
      $file = array('pic' => Input::file('pic'));
      $destinationPath = 'storage/images'; // upload path
      $extension = Input::file('pic')->getClientOriginalExtension();
      $fileName = rand(11111,99999).'.'.$extension; // renaming image
      Input::file('pic')->move($destinationPath, $fileName);
          // $post = [
          //   "caption"   => $caption,
          //   "location"    => $location,
          //   "dog_id" => $dog_id,
          //   "pic"  => $fileName
          // ];
      $post = new Posts;
      $post->caption = $caption;
      $post->location = $location;
      $post->dog_id = $dog_id;
      $post->pic = $fileName;

      try {
        $post->save();
          return response()->json(['success'=> true, 'message'=> "Successfully posted!!"]);
      }
      catch (Exception $e) {
        return response()->json(['success'=> false, 'error'=> $e]);
      }
    }
    else {
      // $post = new Posts;
      // $post->caption = $caption;
      // $post->location = $location;
      // $post->dog_id = $dog_id;
      // try {
      //     $post->save();
      //     return response()->json(['success'=> true, 'message'=> "Successfully posted!!"]);
      // }
      // catch (Exception $e) {

        return response()->json(['success'=> false, 'error'=> "You have to upload picture"]);
      }
  }

  public function deletePost($post_id)
  {
    try{
      $post = $this->posts->find($post_id);
      Storage::delete('public/images/'.$post->pic);
      $delete = $this->posts->where('id',$post_id) ->delete();
        return response()->json(['success'=> true, 'message'=> "Post Successfully Deleted!!"]);
    }
    catch(Exception $ex)
    {
      return response()->json(['success'=> false, 'error'=> $ex]);
    }
  }



  public function viewAllPosts($dog_id)
  {
    try{
      $posts = $this->posts->where('dog_id',$dog_id)->get();
      return response()->json($posts);
    }
    catch(Exception $ex)
    {
      return response()->json(['success'=> false, 'error'=> $ex]);
    }
  }

  public function viewPost($post_id)
  {
    try{
      $posts = $this->posts->where('id',$post_id)->get();
      return response()->json($posts);
    }
    catch(Exception $ex)
    {
      return response()->json(['success'=> false, 'error'=> $ex]);
    }
  }

  public function updatePost(Request $request, $post_id)
  {
    $query = $this->posts->find($post_id);

    $query->caption = $request->caption;
    $query->location = $request->location;

    try {
      if( ($query->caption=="") && ($query->pic=="")  )
      {
        return response()->json(['success'=> false, 'error'=> "Update failed. Post will be empty!!"]);
      }
      else {
        $update =  $query->save();
        return response()->json(['success'=> true, 'message'=> "Post Updated!"]);
      }
    } catch (Exception $e) {
      return response()->json(['success'=> false, 'error'=> $e]);
    }
  }

  public function likeCount($post_id)
  {
    $query = $this->likes->where('post_id','=',$post_id)->get();
    return response()->json(['likes'=>count($query)]);
  }

  public function exploreByLikes() //popular posts
  {
    $query = $this->posts->withCount('likes')->orderBy('likes_count', 'desc')->get(); //take= ambil brp records ->take(9)->get();
    return $query;
  }
  public function postYouMightLike()  //based on user's doggie
  {
    $query = $this->doggie->where('owner_id',auth()->user()->id)->get();
    $dogtypes = array();
    $post = array();
    $count = 0;
    for($i=0;$i<count($query);$i++)
    {
        $dogtypes[$count] = $query[$i]->breed;
        $count = $count + 1;
    }
    $random = rand(0, $count-1);

    $dogs = $this->doggie->where([ ['owner_id','!=',auth()->user()->id],['breed','=',$dogtypes[$random]] ])->inRandomOrder()->take(10)->get(); //ambil 10 doggie yg breednya sama kyk salah satu anjing dia
    for($i=0;$i<count($dogs);$i++)
    {
        $posts = $this->posts->withCount('likes')->where('dog_id',"=",$dogs[$i]->id)->orderBy('likes_count', 'desc')->first();//ambil post doggienya yg paling banyk like
        $post[$i] = $posts->id;
    }
    return $post;
  }

}
