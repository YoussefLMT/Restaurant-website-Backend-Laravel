<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    
    function addToCart(Request $request, $meal_id)
    {

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
                'meal_id'=> $meal_id,
                'quantity'=> $request->quantity,
            ]);
            
            return response()->json([
                'status' => 200,
                'message' => "Added to cart successfully",
            ]);
        }
    }


    function getCartCount()
    {
        $cart_count = Cart::where('user_id', auth()->user()->id)->count(); 

        return response()->json([
            'status' => 200,
            'cart_count' => $cart_count,
        ]);
    }


    function getCartMeals()
    {

        $cart_meals = DB::table('cart')
        ->join('meals', 'cart.meal_id', '=', 'meals.id')
        ->where('cart.user_id', auth()->user()->id)
        ->select('meals.*', 'cart.quantity')
        ->get();

        return response()->json([
            'status' => 200,
            'cart_meals' => $cart_meals,
        ]);
    }


    function removeMealFromCart($id){

        $meal = Cart::find($id);

        if($meal){

            $meal->delete();
    
            return response()->json([
                'status' => 200,
                'message' => 'Deleted successfully',
            ]);

        }else{

            return response()->json([
                'status' => 404,
                'message' => 'Meal not found!',
            ]);
        }
    }

}
