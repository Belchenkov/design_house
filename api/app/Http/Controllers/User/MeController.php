<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function me()
    {
        if (auth()->check()) {
            return response()->json([
               "user" => auth()->user()
            ]);
        }

        return response()->json([
            "user" => null
        ]);
    }
}
