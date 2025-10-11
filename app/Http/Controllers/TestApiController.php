<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestApiController extends Controller
{
    //defau;t method
    // --- IGNORE ---
    function index() {
        return response()->json([
            'status' => 'success',
            'message' => 'API is working']);
    }
}
