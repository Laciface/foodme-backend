<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodList extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable =[
        'id_user_from',
        'food_id'
    ];

    public function find(){

    }

}
