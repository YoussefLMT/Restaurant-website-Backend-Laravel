<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    
    public function getTotalCount(){

        $mealsCount = Product::all()->count();
        $usersCount = User::all()->count();
        $ordersCount = Order::all()->count();

        $income = DB::table('orders')
        ->join('order_meals', 'order_meals.order_id', '=', 'orders.id')
        ->join('meals', 'order_meals.mealst_id', '=', 'meals.id')
        ->sum('meals.price');

        return response()->json([
            'status' => 200,
            'mealsCount' => $mealsCount,
            'usersCount' => $usersCount,
            'ordersCount' => $ordersCount,
            'income' => $income
        ]);
    }
}
