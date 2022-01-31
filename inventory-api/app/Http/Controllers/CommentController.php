<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index($id){
        $post = Post::find($id);

        if(!$post){
            return response([
                'message' => 'post Not Found'
            ],403);
        }

        return response([
            'post'=> $post->comments()->with('user:id,name,img')->get()
        ], 200);

    }

    public function store(Request $request, $id){

        $post = Post::find($id);

        if(!$post){
            return response([
                'message' => 'post Not Found'
            ],403);
        }


        $attrs = $request->validate([
            'body' => 'string|required',
        ]);

        Comment::create([
            'body' => $attrs['body'],
            'post_id' => $id,
            'user_id' => auth()->user()->id
        ]);

        return response([
            'message' => 'Comment Shared',
            'post' => $post
        ], 200);

    }

    public function update(Request $request, $id){
        $comment = Comment::find($id);

        if(!$comment){
            return response([
                'message' => 'Comment Not Found'
            ],403);

        }

        if(!$comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission Denied'
            ], 403);
        }

        $attrs = $request->validate([
            'body' => 'string|required',
        ]);

        $comment->update([
            'body' => $attrs['body']
        ]);

        return response([
            'message' => 'Comment UpdatedW'
        ]);

    }

    public function destroy(Request $request, $id){

        $comment = Comment::find($id);

        if(!$comment){
            return response([
                'message' => 'Comment Not Found'
            ],403);

        }

        if(!$comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission Denied'
            ], 403);
        }

        $comment->delete();

        return response([
            'message' => 'Comment Removed',
        ]);

    }

}
