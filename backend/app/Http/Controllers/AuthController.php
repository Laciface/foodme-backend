<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email',
        'password'=> 'required'
    ]);

    if($validator->fails()){
        return response()->json(['status_code'=> 400, 'message'=> 'Bad request']);
        }
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->save();
    return redirect('http://127.0.0.1:3000');
//    return response()->json([
//        'status_code'=> 200,
//        'message'=> 'User create successfully'
//    ]);
}
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['status_code'=> 400, 'message'=> 'Bad request']);
        }

        $credential = request(['email', 'password']);

        if(!Auth::attempt($credential)){
            return response()->json([
                'status_code'=> 500,
                'message'=> 'Unauthorized'
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $tokenResult = $user->createToken('authToken')->plainTextToken;
        return redirect('http://127.0.0.1:3000');
           /*return response()->json([
            'status_code' => 200,
            'token' => $tokenResult]);*/
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status_code'=> 200,
            'message'=> 'Token deleted successfully'
        ]);
    }

    public function getUser(Request $request){
        $user = User::where('name', $request->email)->first();
    }
}
