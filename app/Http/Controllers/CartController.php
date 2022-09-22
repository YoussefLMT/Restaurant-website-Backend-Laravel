<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    
    function addToCart(Request $request, $meal_id){

        $meal_in_cart = Cart::where('user_id', auth()->user()->id)
                ->where('meal_id', $meal_id)
                ->count();

        if($meal_in_cart > 0){

            return response()->json([
                'status' => 406,
                'message' => "This meal is already in you cart!",
            ]);

        }else{

            Cart::create([
                'user_id'=> auth()->user()->id,
                'meal_id'=> $meal_id
            ]);
            
            return response()->json([
                'status' => 200,
                'message' => "Added to cart successfully",
            ]);
        }
    }

}
