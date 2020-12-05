<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth:sanctum')->except('login');
    }

    public function profile(Request $request)
    {

        try {
            $user = Auth::user();
            $role=$user->getRoleNames()->first();
            $user['role']=$role;
            return response()->json([
                'data'=>$user,
                'token'=>Auth::user()->createToken("token")->plainTextToken,
                'token_type'=>'Bearer'
            ],201);
        } catch (\Exception $exception) {
            return response()->json([
                'message'=>Lang::get("Unauthenticate")
            ],400);
        }

    }

    public function logout(Request $request)
    {
        $user=$request->user();
        $user->tokens()->delete();
        return response()->json([
            'message' => Lang::get("messages.auth.logout.success"),
        ],200);
    }

    public function login(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'email'=>"required|email",
            "password" => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message'=>implode(PHP_EOL,$validator->messages()->all())
            ],400);
        }

        $credential = [
            'email'=>$request->get('email'),
            'password' => ($request->get('password')),
        ];
        $authenticated=Auth::attempt($credential);
        if ($authenticated) {
            $user = Auth::user();
            $role=$user->getRoleNames()->first();
            $user['role']=$role;
            return response()->json([
                'data'=>$user,
                'token'=>Auth::user()->createToken("token")->plainTextToken,
                'token_type'=>'Bearer'
            ],201);
        }else{
            return response()->json([
                'message'=>Lang::get("messages.credential.invalid")
            ],400);
        }

    }
}
