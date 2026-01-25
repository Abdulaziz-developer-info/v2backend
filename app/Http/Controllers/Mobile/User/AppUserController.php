<?php

namespace App\Http\Controllers\Mobile\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppUserController extends Controller
{
    public function user_info(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'status' => true,
            'data' => $user,
        ]);
    }

}
