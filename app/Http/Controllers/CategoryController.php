<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function get($id = null)
    {
        if($id){
            $categories = Category::findOrFail($id);
            return response()->json([
                'statusCode' => 200,
                'message' => 'success',
                'data' => $categories
            ]);          
        }

        $categories = Category::all();
        return response()->json([
            'statuscode' => 200,
            'message' => 'success',
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $statuscode = 201;
        $error = null;
        $data = null;
        $message = 'success';

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5'
        ]);

        if($validator->fails()){
            $error = [];
            $statuscode = 400;
            $message = "Bad Request";
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                array_push($error, $message);
            }
        } else {
            $category = new Category;
            $category->name = $request->input('name');
            if($category->save()){
                $data = $category;   
            } else {
                $statuscode = 400;
                $message = 'Bad Request';
            };
        }

        return response()->json([
            'statuscode' => $statuscode,
            'message' => $message,
            'data' => $data,
            'error' => $error
        ], $statuscode);
    }

    public function update(Request $request, $id)
    {
        $statuscode = 201;
        $error = null;
        $data = null;
        $message = 'success';
        if($id)
        {

            $validator = Validator::make($request->all(), [
                'name' => 'required|min:5'
            ]);

            if($validator->fails()){
                $error = [];
                $statuscode = 400;
                $message = "Bad Request";
                $errors = $validator->errors();
                foreach ($errors->all() as $message) {
                    array_push($error, $message);
                }
            } else {
                $category = Category::findOrFail($id);
                if($category){
                    $category->name = $request->input('name');
                    if($category->save()){
                        $statuscode = 200;
                        $data = $category;
                    }
                } else {
                    $statuscode = 400;
                    $message = 'Bad Request';
                };
            }
        } else {
            $statuscode = 400;
            $message = 'Bad Request';
        }

        return response()->json([
            'statuscode' => $statuscode,
            'message' => $message,
            'data' => $data,
            'error' => $error
        ], $statuscode);
    }

    public function delete($id)
    {
        $statuscode = 200;
        $message = 'success';
        $error = null;
        $data = null;

        if($id){
            $category = Category::findOrFail($id);
            if($category){
                if(!$category->delete()){
                    $message = 'Bad Request';
                    $statuscode = 400;
                }
            } else {
                $message = 'Bad Request';
                $statuscode = 400;
            }
        } else {
            $message = 'Bad Request';
            $statuscode = 400;
        }

        return response()->json([
            'statuscode' => $statuscode,
            'message' => $message,
            'data' => $data,
            'error' => $error
        ], $statuscode);
    }
}
