<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\UserRole;

class AuthController extends Controller
{

    public function signup(Request $request)
    {
        $auth = Auth::user(); 

        $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $input['enabled'] = true;
        $input['lastInsertUpdateBy'] = $auth->firstName.' '.$auth->lastName;

        $user = User::create($input); 

        DB::table('user_roles')->where('userId', '=', $user->id)->delete();

        $data = array(); 

        foreach($request->roles as $key => $role) {
            $userRole = new UserRole($role);
            $userRole->userId = $user->id;
            $userRole->lastInsertUpdateBy = $auth->firstName.' '.$auth->lastName;
            $userRole->lastInsertUpdateTS = Carbon::now();

            $userRole->save();
        }

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

        if(!$user->enabled) {
             return response()->json(['error'=>'Login failed! User account has been disabled.'], 401);
        }
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
        $data = $request->user();
        $data->roles = DB::table('user_roles')
                    ->select('user_roles.role', 'user_roles.houseName')
                    ->where('userId', '=', $request->user()->id)
                    ->get();

        return response()->json($data);
    }

    public function getUsers() 
    { 
        $users = DB::table('users')
                    ->select('users.id', 'users.title', 'users.firstName', 'users.lastName', 'users.username', 
                             'users.lastInsertUpdateBy', 'users.updated_at', 'users.enabled')
                    ->get();

        foreach ($users  as $key => $user) {
                 $user->roles = DB::table('user_roles')
                                    ->where('userId', '=',  $user->id)
                                    ->get();
        }

        return response()->json($users); 
    } 

    public function updateUser(Request $request, $id) 
    {
        $user = User::findOrFail($id);

        $auth = Auth::user(); 

        if($user) {
            $user->title = $request->title;
            $user->firstName = $request->firstName;
            $user->lastName = $request->lastName;
            $user->username = $request->username;
            $user->lastInsertUpdateBy = $auth->firstName.' '.$auth->lastName;
            $user->save();
        }

        DB::table('user_roles')->where('userId', '=', $id)->delete();

        $data = array(); 

        foreach($request->roles as $key => $role) {
            $userRole = new UserRole($role);
            $userRole->userId = $id;
            $userRole->lastInsertUpdateBy = $user->firstName.' '.$user->lastName;
            $userRole->lastInsertUpdateTS = Carbon::now();

            $userRole->save();
        }

        return response()->json(['success'=>true]);
    }

    public function disableUser($id) 
    {
        $user = User::findOrFail($id);

        $auth = Auth::user(); 

        if($user) {
            $user->enabled = false;
            $user->lastInsertUpdateBy = $auth->firstName.' '.$auth->lastName;
            $user->save();
        }

        return response()->json(['success'=>true]);
    }

    public function enableUser($id) 
    {
        $user = User::findOrFail($id);

        $auth = Auth::user(); 

        if($user) {
            $user->enabled = true;
            $user->lastInsertUpdateBy = $auth->firstName.' '.$auth->lastName;
            $user->save();
        }

        return response()->json(['success'=>true]);
    }

    public function resetPassword(Request $request, $id) 
    {
        $user = User::findOrFail($id);

        $auth = Auth::user(); 

        if($user) {
            $user->password =  bcrypt($request->password); 
            $user->lastInsertUpdateBy = $auth->firstName.' '.$auth->lastName;
            $user->save();
        }

        return response()->json(['success'=>true]);
    }    
}