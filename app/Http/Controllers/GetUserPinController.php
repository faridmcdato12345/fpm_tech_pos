<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GetUserPinController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $user = User::where('pin',$request->pin)->get();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
        
    }
}
