<?php

namespace App\Http\Controllers;

use App\Models\TypeBike;
use Illuminate\Http\Request;

class TypeBikeController extends Controller
{

    public function index() 
    {
       $types_bikes = TypeBike::all();
   
        return response()->json([
                'status' => true,
                'types_bikes' => $types_bikes
        ],200); 
    }

}
