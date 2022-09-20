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
                'message' => "Meal added successfully"
            ]);
        }
    }



    public function getMeal($id)
    {
        $meal = Meal::find($id);

        if($meal){

            return response()->json([
                'status' => 200,
                'meal' => $meal,
            ]);

        }else{

            return response()->json([
                'status' => 404,
                'message' => 'Meal not found!',
            ]);

        }
    }



    public function updateMeal(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'price'=> 'required',
            'category' => 'required',
            'description' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);


        if($validator->fails()){

            return response()->json([
                'status' => 422,
                'validation_err' => $validator->messages(),
            ]);

        }else{

            $meal = Meal::find($id);

            if($meal){

                $meal->name = $request->name;
                $meal->price = $request->price;
                $meal->category = $request->category;
                $meal->description = $request->description;
    
                // if($request->hasFile('image'))
                // {
                //     $path = $product->image;
                //     if(File::exists($path)){
                //         File::delete($path);
                //     }
                //     $image = $request->file('image');
                //     $extension = $image->getClientOriginalExtension();
                //     $image_name = time(). '.' .$extension;
                //     $image->move('uploads/images', $image_name);
                //     $product->image = 'uploads/images/' . $image_name;
                // }
    
                $meal->update();
        
                return response()->json([
                    'status' => 200,
                    'message' => "Updated successully"
                ]);

            }else{

                return response()->json([
                    'status' => 404,
                    'message' => 'Meal not found!'
                ]);
            }
        }
    }



    public function deleteMeal($id)
    {
        $meal = Meal::find($id);

        if($meal){

            $meal->delete();
    
            return response()->json([
                'status' => 200,
                'message' => 'Deleted successfully'
            ]);

        }else{

            return response()->json([
                'status' => 404,
                'message' => 'Meal not found!'
            ]);
        }
    }



    public function getSpecificMeals()
    {
        $home_meals = Meal::limit(8)->get();

        return response()->json([
            'status' => 200,
            'home_meals' => $home_meals
        ]);
    }


}
