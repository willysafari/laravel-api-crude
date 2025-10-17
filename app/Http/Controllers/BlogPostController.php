<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Str;
use App\Models\Post;
use App\Models\BlogCategory;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $posts = Post::get();
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate request
        $validatedData = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'blog_category_id' => 'required|integer|exists:blog_categories,id',
            'user_id' => 'required|integer|exists:users,id',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        // --returnn fails of code
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validatedData->errors()

            ], 422);
        }
        // varify the login user
        $LoggedInUser = auth()->user();
        if ($LoggedInUser->id !== $request->input('user_id')) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized action admin',
                'id' => $LoggedInUser->id,
            ], 403);
        }

        // check if the category existing the db
        $categoryExists = BlogCategory::find($request->input('blog_category_id'));
        if (!$categoryExists) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Category does not exist'
            ], 404);
        }
        // handle file upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/thumbnails'), $fileName);
            $thumbnailPath = 'storage/thumbnails/' . $fileName;
        }
        $data['thumbnail'] = $thumbnailPath;
        // create blog post
        $data['title'] = $request->input('title');
        $data['slug'] = Str::slug($request->title);
        $data['content'] = $request->input('content');
        $data['blog_category_id'] = $request->input('blog_category_id');
        $data['user_id'] = $request->input('user_id');
        $data['thumbnail'] = $thumbnailPath ? $thumbnailPath : null;
        $data['excerpt'] = substr($request->input('content'), 0, 100) . '...';
        // validate the role of the user
        if (Auth::user()->role == 'admin') {
            $data['status'] = 'published';
            $data['published_at'] = now();
        } else {
            $data['status'] = 'draft';
            $data['published_at'] = null;
        }
        $blogPost = Post::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Blog post created successfully',
            'data' => $blogPost
        ], 201);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $blogPost = Post::find($id);
        if (!$blogPost) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Blog post not found'
            ], 404);
        }
        //validate the request
        $validatedData = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required',
            'blog_category_id' => 'sometimes|required|integer|exists:blog_categories,id',
            'status' => 'sometimes|required|in:draft,published',
            'excerpt' => 'sometimes|string|max:255',
            'user_id' => 'sometimes|required|integer|exists:users,id',

        ]);

        // return error message if validation fails
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validatedData->errors()
            ], 422);
        }
        $LoggedInUser = auth()->user();
        if ($LoggedInUser->id !== $request->input('user_id')) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized action admin',
                'id' => $LoggedInUser->id,
            ], 403);
        }

        // check if the category existing the db
        $categoryExists = BlogCategory::find($request->input('blog_category_id'));
        if (!$categoryExists) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Category does not exist'
            ], 404);
        }

        $blogPost['title'] = $request->input('title', $blogPost->title);
        $blogPost['slug'] = Str::slug($request->input('title', $blogPost->title));
        $blogPost['content'] = $request->input('content', $blogPost->content);
        $blogPost['user_id'] = $request->input('user_id', $blogPost->user_id);
        $blogPost['blog_category_id'] = $request->input('blog_category_id', $blogPost->blog_category_id);
        $blogPost['excerpt'] = substr($blogPost['content'], 0, 100) . '...';
        $blogPost['status'] = $request->input('status', $blogPost->status);
        $blogPost->save();

        // rreturn success response
        return response()->json([
            'status' => 'success',
            'message' => 'Blog post updated successfully',
            'data' => $blogPost
        ], 200);

    }

    public function blogImagePost(Request $request, int $id)
    {
        $blogPost = Post::find($id);
        if (!$blogPost) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Blog post not found'
            ], 404);
        }
        // validate the request
        $validatedData = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'thumbnail' => 'required|image|max:2048',
        ]);

        // return error message if validation fails
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validatedData->errors()
            ], 422);
        }
        

           $LoggedInUser = auth()->user();
        if ($LoggedInUser->id !== $request->integer('user_id')) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized action admin',
                'id' => $LoggedInUser->id,
            ], 403);
        }
        // handle file upload
         $thumbnailPath = null;
        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/thumbnails'), $fileName);
            $thumbnailPath = 'storage/thumbnails/' . $fileName;
        }
        $blogPost['thumbnail'] = $thumbnailPath ? $thumbnailPath : $blogPost->thumbnail;
        $blogPost->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog post image updated successfully',
            'data' => $blogPost
        ], 200);    
       
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $blogPost = Post::find($id);
        if (!$blogPost) {
            return response()->json([
                'status' => 'failed',  
                'message' => 'Blog post not found'
            ], 404); 
        } 
        // delete the post
        $blogPost->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Blog post deleted successfully'
        ], 200);      
    }
}
