<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Like;
class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function react(Request $request)
    {
        //validate variables
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|integer|exists:posts,id',
            'status' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation passed'
            ], 400);
        }

        $userID = auth()->user()->id;
        $postID = $request->post_id;
        $status = $request->status;

        $like = Like::where('user_id', $userID)->where('post_id', $postID)->first();

        if ($like) {
            if ($like->status == $status) {
                //if same status, remove like/dislike
                $like->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Reaction removed'
                ], 200);
            } else {
                //update status
                $like->status = $status;
                $like->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Reaction updated'
                ], 200);
            }
        } else {
            //create new like/dislike
            Like::create([
                'user_id' => $userID,
                'post_id' => $postID,
                'status' => $status
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Reaction added'
            ], 201);
        }

    }

    /**
     * Display the specified resource.
     */
    public function Reactions(Request $request,$postId)
    {
        $likesCount = Like::where('post_id', $postId)->where('status', 1)->count();
        $dislikesCount = Like::where('post_id', $postId)->where('status', 2)->count();

        return response()->json([
            'likes' => $likesCount,
            'dislikes' => $dislikesCount,
            'post_id' => $postId 
        ], 200);
    }
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
