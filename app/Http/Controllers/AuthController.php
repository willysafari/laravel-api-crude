<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
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
    public function register(Request $request)
    {
        //validate the valiable
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // check if the validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); 
        }
        // create user
        $data = $request->only('name', 'email', 'password');
        //  'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password),
        $user = User::create($data
           
        );
        // return response
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);    
        
    }


    public function login(Request $request)
    {
        //Validate the variable
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        // check if the validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => $validator->errors(),
            ], 422);
        }

        // check if the user exists
        // $user = User::where('email', $request->email)->first();

        if(Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'Success',
                'message' => 'User successfully logged in',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        // create token
    }else{
        return response()->json([
            'status' => 'Failed',
            'message' => 'Invalid email or password',
        ], 401);
    }

}

public function profile(Request $request)
    {
        $user =Auth::user();
        return response()->json([
            'status' => 'Success',
            'user' => $user,
        ], 200);
    }
    /**
     * Display the specified resource.
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'status' => 'Success',
            'message' => 'User successfully logged out',
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
