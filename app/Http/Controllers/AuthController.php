<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['register']])
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'require|string|between:2,100',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);
        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ], 422);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json(['message'=>'User created successfully','user' => $user]);
    }

    protected function guard(){
        return Auth::guard();
    }

    public function profile() {
        return response()->json($this->guard()->user());
    }

    public function refresh(){
        return $this->respondWithToken($this->guard()->refresh());
    }
}
