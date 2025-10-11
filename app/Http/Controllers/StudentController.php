<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
// use Dotenv\Validator;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $students = Student::get(); // Replace with actual data retrieval logic
        return response()->json([
            'status' => 'success',
            'data' => $students
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'gender' => 'required|in:male,female,other'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create the student
        $student = Student::create($request->only(['name', 'email', 'gender']));

        return response()->json([
            'status' => 'success',
            'data' => $student
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $student = Student::find($id);
        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $student
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //validate
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:students,email,' . $id,
            'gender' => 'sometimes|required|in:male,female,other'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        // Find the student
        $student = Student::find($id);
        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }

        // Update the student
        $student->update($request->only(['name', 'email', 'gender']));
        return response()->json([
            'status' => 'success',
            'data' => $student
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // find the student
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }

        $student->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Student deleted successfully'
        ], 200);
    }
}
