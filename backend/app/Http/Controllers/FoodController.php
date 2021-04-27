<?php

namespace App\Http\Controllers;

use App\Models\FoodList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\APIController;

class FoodController extends Controller
{
    public function addFav(Request $request)
    {
        try {
            $food = new FoodList();
            $food->food_id = $request->food_id;
            $food->food_photo = $request->photo;
            $food->food_name = $request->name;
            $food->id_user_from = auth()->user()->id;
            $food->save();

            return response()->json([
                'status_code' => 200,
                'message' => 'User create successfully'
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Something went wrong'], 400);
        }
    }

    public function showFav()
    {
        try {
            $user_id = auth()->user()->id;
            $foodList = FoodList::where('id_user_from', $user_id)->get();
            return response()->json(json_decode($foodList), 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Something went wrong'], 400);
        }
    }
}
