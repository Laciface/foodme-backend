<?php

namespace App\Http\Controllers;

use App\Models\FoodList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\APIController;

class FoodController extends Controller
{
    public function addFav(Request $request){
    try {
        $food = new FoodList();
        $food->food_id = $request->food_id;
        $food->id_user_from = auth()->user()->id;
        $food->save();

        return response()->json([
            'status_code'=> 200,
            'message'=> 'User create successfully'
        ]);
        } catch(\Exception $e){
            Log::error($e->getMessage());
            return response()->json(['message'=>'Something went wrong'], 400);
        }
    }

    public function showFav(){
        try {
            $user_id = auth()->user()->id;
            $foodList =  FoodList::where('id_user_from', $user_id)->get();
            $foodIdList = [];
            $objects = [];
            foreach ($foodList as $item){
                array_push($foodIdList, $item['food_id']);
            }
            $response = array();

            foreach($foodIdList as $id){
                $curl = curl_init();
                $url = 'https://www.themealdb.com/api/json/v1/1/lookup.php?i=' . $id;
                curl_setopt($curl, CURLOPT_URL, $url.$id);
                //curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $objects = $curl;
            }


            $mh = curl_multi_init();

            foreach ($objects as $key => $curl) {
                curl_multi_add_handle($mh,$curl);
            }

            //execute the multi handle
            do {
                $status = curl_multi_exec($mh, $active);
                if ($active) {
                    curl_multi_select($mh);
                }
            } while ($active && $status == CURLM_OK);

            //close the handles
            foreach ($objects as $key => $curl) {
                curl_multi_remove_handle($mh, $curl);
            }
            curl_multi_close($mh);


            // get all response
            foreach ($objects as $key => $curl) {
                array_push($response, curl_multi_getcontent($curl));
            }

            return $response;

        } catch(\Exception $e){
            Log::error($e->getMessage());
            return response()->json(['message'=>'Something went wrong'], 400);
        }
    }
}
