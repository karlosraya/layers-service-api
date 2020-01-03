<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;

class AuthController extends Controller
{

    public function signup(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $input['enabled'] = true;

        $user = User::create($input); 

        return response()->json([
            'message' => 'User successfully created!'
        ], 201);
    }
  

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['username', 'password']);

        if(!Auth::attempt($credentials))
            return response()->json(['error'=>'Login failed! Please double check username and passowrd and try again.'], 401);
        
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();
        
        $success['token'] =  $tokenResult->accessToken; 

        return response()->json(['success' => $success]); 
    }
  
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['success'=>true]); ; 
    }
  
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function getUsers() 
    { 
        $users = DB::table('users')->get();

        return response()->json($users, $this-> successStatus); 
    } 
}