<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderMeal;

class OrderController extends Controller
{
    
    function getOrderTotalPrice(){

        $user_id = auth()->user()->id;

        $total_price = DB::table('cart')
        ->join('meals', 'cart.meal_id', '=', 'meals.id')
        ->where('cart.user_id', $user_id)
        ->sum(DB::raw('meals.price * cart.quantity'));

        return response()->json([
            'status' => 200,
            'total_price' => $total_price
        ]);
    }

}
