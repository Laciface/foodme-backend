<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:4',
                'email' => 'required|email',
                'password'=> 'required|min:5'
            ]);

            if($validator->fails()){
                return response([ 'message'=> 'Some input are invalid'], 400);
            }
            User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=>bcrypt($request->password)
            ]);

            return response()->json(['message'=> 'Registration was successfully'], 200);

        } catch(\Exeption $error){
            Log::error($error->getMessage());
            return response([ 'message'=> 'Something went wrong'], 400);
        }
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
                'message'=> 'Unauthorized'
            ], 500);
        }

        $user = User::where('email', $request->email)->first();
        $tokenResult = $user->createToken('authToken')->plainTextToken;
        return response()->json([
            'token' => $tokenResult,
            'name'=>$user->name], 200);
    }
}
