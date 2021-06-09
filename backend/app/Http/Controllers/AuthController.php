<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

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
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return response()->json(['message' => 'Registration was successfully'], 200);

        } catch(\Exeption $error){
            Log::error($error->getMessage());
            return response([ 'message' => 'Something went wrong'], 400);
        }
}
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }

        $credential = request(['email', 'password']);

        if(!Auth::attempt($credential)){
            return response()->json([
                'message' => 'Unauthorized'
            ], 500);
        }

        $user = User::where('email', $request->email)->first();
        $tokenResult = $user->createToken('authToken')->plainTextToken;
        return response()->json([
            'token' => $tokenResult,
            'name'=>$user->name,
            'user_id' =>$user->id], 200);
    }

    public function editProfile(Request $request){
        $user_id = auth()->user()->id;
        $image = $request->picture;
        $userName = User::where('id', $user_id)->select('name')->get('name');
        $fileName = $userName[0]['name'] . '.' . $image->getClientOriginalExtension();

        $img = Image::make($image->getRealPath());
        $img->resize(120, 120, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->stream();
        Storage::disk('local')->put('public/images/'. $fileName, $img);

        if($request->country == null){
            $countryDictionary = User::where('id', $user_id)->select('country')->get('country');
            $country = $countryDictionary[0]['country'];
        } else {
            $country = $request->country;
        }

        if($request->introduction == null){
            $introductionDictionary = User::where('id', $user_id)->select('introduction')->get('introduction');
            $introduction = $introductionDictionary[0]['introduction'];
        } else {
            $introduction = $request->introduction;
        }

        /*if($request->picture == null){
            $pictureDictionary = User::where('id', $user_id)->select('picture')->get('picture');
            $picture = $pictureDictionary[0]['picture'];
        } else {
            $picture = $request->picture;
        }*/



        $this->validate($request,
            [
                'country' => 'min:4',
                'introduction' => 'min:4',
                'image' => "image|mimes:jpg,png,jpeg|max:15000"
            ],
            [
                'country.min' => 'The country must has at least 4 chars',
                'introduction.min' => 'The introduction must has at least 20 chars',
                'image.image' => 'This should be an image file',
                'image.mimes' => 'The allowed extensions: jpg,png,jpeg',
                'image.max' => 'The allowed file size is 15000 bite'
            ]);

        $arrayToUpdate = array('country' => $country, 'introduction' => $introduction, 'picture' => $fileName);

        User::where('id', $user_id)->update($arrayToUpdate);
        return response()->json(['message' => 'Profile updated'], 200);
    }

    public function getProfileData($id){
        $userInfo = User::where('id', $id)->get();
        return response()->json($userInfo, 200);
    }
}
