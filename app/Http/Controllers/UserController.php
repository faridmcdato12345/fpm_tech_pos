<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $query = User::query();
        $queryPayload = $request->query('name');
        if($request->has('name')){
            $query->whereHas('products', function ($q) use ($queryPayload){
                return $q->where('fullname','like','%' . $queryPayload . '%')->orWhere('username','like','%' . $queryPayload . '%');
            });
        }
        $limit = $request->has('query') ? $request->query('query'): 5;
        $users = $query->paginate(intval($limit));
        return inertia('User/Index',[
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'users' => (UserResource::collection($users))->resolve()
        ]);
    }
}
