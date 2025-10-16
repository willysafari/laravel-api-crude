<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Validator;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $category = BlogCategory::get();


        return response()->json([
            'status' => 'Success',
            'count' => count($category),
            'categories' => $category
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:blog_categories',

        ]);
        // response fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors(),
            ], 422);
        }

        $data['name'] = $request->name;
        $data['slug'] = Str::slug($request->name);
        // create category
        $category = BlogCategory::create(
            $data

        );

        return response()->json([
            'status' => 'Success',
            'message' => 'Category created successfully',
            'category' => $category
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
        //validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        // response fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors(),
            ], 422);
        }
        $category = BlogCategory::find($id);
        if (!$category) {
            return response()->json([   
                'status' => 'Error',
                'message' => 'Category not found',
            ], 404);
        }else{
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->save();

            return response()->json([
                'status' => 'Success',
                'message' => 'Category updated successfully',
                'category' => $category
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category =BlogCategory::find($id);


        if(!$category){
            return response()->json([
                'status' => 'Error',
                'message' => 'Category not found',
            ], 404);
        }else{
         BlogCategory::destroy($id);

          return response()->json([
            'status' => 'suceess',
              'message' => 'catgeory deleted successfully'
          ]);
        }
    }
}
