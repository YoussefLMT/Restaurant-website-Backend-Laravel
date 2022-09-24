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



    function placeOrder(Request $request){
        
        $user_id = auth()->user()->id;

        $cart_data = Cart::where('user_id', $user_id)->get();

        $total_price = DB::table('cart')
        ->join('meals', 'cart.meal_id', '=', 'meals.id')
        ->where('cart.user_id', $user_id)
        ->sum(DB::raw('meals.price * cart.quantity'));

        $order = Order::create([
            'user_id' => $user_id,
            'address' => $request->address,
            'city' => $request->city,
            'phone' => $request->address,
            'total_amount' => $total_price
        ]);

        foreach($cart_data as $cart){

            $order_meals = new OrderMeal;
            $order_meals->order_id = $order->id;
            $order_meals->meal_id = $cart->meal_id;
            $order_meals->save();
            
            Cart::where('user_id', $user_id)->delete();
        }
        
        return response()->json([
            'status' => 200,
            'message' => "Your order has been placed successfully",
        ]);
    }



    function getUserOrders(){

        $user_id = auth()->user()->id;

        $orders = DB::table('orders')
        ->join('order_meals', 'order_meals.order_id', '=', 'orders.id')
        ->join('meals', 'order_meals.meal_id', '=', 'meals.id')
        ->where('orders.user_id', $user_id)
        ->get();

        if($orders){

            return response()->json([
                'status' => 200,
                'orders' =>  $orders
            ]);

        }else{

            return response()->json([
                'status' => 401,
                'message' =>  "You don't have any order yet"
            ]);
        }
    }



    function getAllOrders(){

        $orders = Order::all();

        return response()->json([
            'status' => 200,
            'orders' =>  $orders
        ]);
    }



    function getOrderMeals($id){

        $order_meals = DB::table('orders')
        ->join('order_meals', 'order_meals.order_id', '=', 'orders.id')
        ->join('meals', 'order_meals.meal_id', '=', 'meals.id')
        ->where('orders.id', $id)
        ->get();

        return response()->json([
            'status' => 200,
            'order_meals' =>  $order_meals
        ]);
    }



    function updateOrderStatus(Request $request, $id){

        $order = Order::find($id);
        
        if($order){

            $order->status = $request->status;
            $order->save();

            return response()->json([
                'status' => 200,
                'message' => "Updated successully",
            ]);

        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Order not found!',
            ]);
        }
    }
}
