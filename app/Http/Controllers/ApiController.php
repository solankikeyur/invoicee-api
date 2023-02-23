<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getSuccessResponse($data = []) {
        return response()->json($data);
    }

    public function getFailureResponse($data = []) {
        return response()->json($data, 500);
    }
}
