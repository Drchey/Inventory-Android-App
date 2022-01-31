<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use PhpParser\Node\Expr\FuncCall;

class PostController extends Controller
{
    //Index

    public function index(){
        return response([
            'posts' =>Post::orderby('created_at', 'desc')->with('user:id,name,image')->withCount('comments')->get()
        ], 200);
    }

    public function show($id){
        return response([
            'post' =>Post::where('id', $id)->withCount('comments')->get()
        ], 200);
    }

    public function store(Request $request){

        $attrs = $request->validate([
            'body' => 'string|required',
            'title' => 'string|required|max:32'
        ]);

        $img = $this->saveImage($request->img, 'posts');

        $post = Post::create([
            'body' => $attrs['body'],
            'title' => $attrs['title'],
            'user_id' => auth()->user()->id,
            'img' => $img
        ]);

        return response([
            'message' => 'Post Shared',
            'post' => $post
        ], 200);

    }

    public function update(Request $request, $id){

        $post = Post::find($id);

        if(!$post){
            return response([
                'message' => 'Post Not Found'
            ],403);
        }

        if($post->user()->id != auth()->user()->id){
            return response([
                'message' => 'Permission Declined'
            ],403);
        }

        $attrs = $request->validate([
            'body' => 'string|required',
            'title' => 'string|required|max:32'
        ]);

        $post->update([
            'body' => $attrs['body'],
            'title'=> $attrs['name'],
        ]);

        return response([
            'message' => 'Post Updated',
            'post' => $post
        ], 200);

    }

    public function destroy($id){

        $post = Post::find($id);

        if(!$post){
            return response([
                'message' => 'Post Not Found'
            ],403);
        }

        if($post->user()->id != auth()->user()->id){
            return response([
                'message' => 'Permission Declined'
            ],403);
        }

        $post->comments()->delete;
        $post->delete;

        return response([
            'message' => 'Post Removed',
            'post' => $post
        ], 200);
    }


}
