<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register','login']]);
    }

    public function register(request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|string|unique:users',
            'password' => 'required|string|confirmed|min:6'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create(
            array_merge($validator->validated(), // Retrieve the validated input
            ['password' => bcrypt($request->password)])
        );

        return response()->json([
            'User Successfully Registered',
            'User'=>$user
            ,201
        ]);
    }

    public function login(request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        //The attempt method accepts an array of key / value pairs as its first argument.
        // The values in the array will be used to find the user in your database table.
        if( !$token = auth()->attempt($validator->validated()) ){
            return response()->json(['error'=>'Unauthorized'],401);
        }

        return $this->create_new_token($token);

    }

    public function profile(){
        return response()->json(auth()->user());
    }

    public function logout(){
        auth()->logout();

        return response()->json(['User Successfully Logged out']);
    }

    public function reset_password(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        if($request->password != $request->new_password){

            $user = auth()->user();
            $user->password = $request->new_password;
            if($user->save()){
                return response()->json(['User password resets Successfully']);
            }
        }

        return response()->json(['error'=>'Unauthorized'],401);

    }

    public function create_new_token($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }
}
