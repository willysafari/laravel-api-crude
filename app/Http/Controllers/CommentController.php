<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;
class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $comments = Comment::all();
        return response()->json([
            'status' => 'success',
            'count' => $comments->count(),
            'data' => $comments
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validation of variable
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|integer|exists:posts,id',
            'content' => 'required|',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'errors' => $validator->errors(),
                'post_id' => $request->post_id
            ], 422);
        }

        $data['post_id'] = $request->post_id;
        $data['user_id'] = auth()->user()->id;
        $data['content'] = $request->input('content');
        $comment = Comment::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment created successfully and waiting for admin approval',
            'data' => $comment
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $comment = Comment::Where('post_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $comment,
            'count' => $comment->count()

        ], 200);
    }
 public function pending(Request $request, $id)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|exists:comments,id',
            'status' => 'required|in:pending,approved,rejected',
        ]); 
        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'errors' => $validator->errors()
            ], 422);
        }
        $comment = Comment::find($request->input('comment_id'));
        $comment['status'] = $request->input('status');
        $comment->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Comment status updated to approved',
            'data' => $comment
        ], 200);
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
