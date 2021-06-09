<?php

namespace App\Http\Controllers;

use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Exception\NameException;
use function PHPUnit\Framework\throwException;

class APIController extends Controller
{

    public function showCategories(){
        try {
            $curl = curl_init();
            $url = 'https://www.themealdb.com/api/json/v1/1/categories.php';
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($curl);
            curl_close($curl);
            return response()->json(json_decode($result),200);
        } catch(\Exception $e){
            Log::error($e->getMessage());
            return response()->json(['message'=>'Something went wrong'], 500);
        }
    }

    public function getDetails($id){
        try {
            $curl = curl_init();
            $url = 'https://www.themealdb.com/api/json/v1/1/lookup.php?i=' . $id;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($curl);
            curl_close($curl);
            return response()->json(json_decode($result),200);
        } catch(\Exception $e){
            Log::error($e->getMessage());
            return response()->json(['message'=>'Something went wrong'], 400);
        }
    }

    public function showMeals($category){
        try {
            $curl = curl_init();
            $url = 'https://www.themealdb.com/api/json/v1/1/filter.php?c=' . $category;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($curl);
            curl_close($curl);
            return response()->json(json_decode($result),200);
        } catch(\Exception $e){
            Log::error($e->getMessage());
            return response()->json(['message'=>'Something went wrong'], 400);
        }
    }



    public function search($word){
        try {
            $curl = curl_init();
            $url = 'https://www.themealdb.com/api/json/v1/1/search.php?s=' . $word;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($curl);
            curl_close($curl);
            return response()->json(json_decode($result),200);
        } catch(\Exception $e){
            Log::error($e->getMessage());
            return response()->json(['message'=>'Something went wrong'], 400);
        }

    }
}
