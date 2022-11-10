<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostUpdateRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::when(request('search'), function($query){
            $query->where('title','like','%'.request('search').'%');
        })->orderBy('id','desc')->paginate(5);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = new Post;
        $post->title = $request->title;
        $file = request()->file('image');
        $file_name = uniqid(time()) . '_' . $file->getClientOriginalName();
        $save_path = ('uploadPhoto');
        $post->image = $save_path . "/$file_name";
        $file->move($save_path, $save_path . "/$file_name");
        $post->save();

        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return $post;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Post $post)
    {
        info($request);
        $post->title = $request->title;
        if($request->hasFile('image')){
            unlink(public_path($post->image));
            $file = request()->file('image');
            $file_name = uniqid(time()) . '_' . $file->getClientOriginalName();
            $save_path = ('uploadPhoto');
            $post->image = $save_path . "/$file_name";
            $file->move($save_path, $save_path . "/$file_name");
        }
        $post->save();

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        unlink(public_path($post->image));
        $post->delete();

        return $post;
    }
}
