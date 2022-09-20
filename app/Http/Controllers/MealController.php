<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;
use Illuminate\Support\Facades\Validator;


class MealController extends Controller
{

    public function getMeals()
    {
        $meals = Meal::all();

        return response()->json([
            'status' => 200,
            'meals' => $meals
        ]);
    }


    
    public function addMeal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'price'=> 'required',
            'category' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if($validator->fails()){

            return response()->json([
                'validation_err' => $validator->messages(),
            ]);

        }else{

            $meal = new Meal;
            $meal->name = $request->name;
            $meal->price = $request->price;
            $meal->category = $request->category;
            $meal->description = $request->description;

            if($request->hasFile('image'))
            {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $image_name = time(). '.' .$extension;
                $image->move('uploads/images', $image_name);
                $meal->image = 'uploads/images/' . $image_name;
            }

            $meal->save();
    
            return response()->json([
                'status' => 200,
                'message' => "Meal added successfully",
            ]);
        }
    }
}
