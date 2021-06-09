<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class FoodList extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    public $timestamps = false;

    protected $fillable =[
        'id_user_from',
        'food_id'
    ];

    public function find(){
    }
}
