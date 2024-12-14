<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        return response()->json(["應用程式介面" => "應用程式介面"], 200);
    }
}
