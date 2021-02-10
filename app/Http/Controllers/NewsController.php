<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function get($id = null)
    {
        if($id){
            $news = News::with('category')->find($id);
            return response()->json([
                'statusCode' => 200,
                'message' => 'success',
                'data' => $news
            ]);
        }
        $news = News::with('category')->get();
        return response()->json([
            'statusCode' => 200,
            'message' => 'success',
            'data' => $news
        ]);
    }

    public function store(Request $request)
    {
        $statuscode = 201;
        $message = 'success';
        $error = null;
        $data = null;

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5',
            'content' => 'required|min:10',
            'category_id' => 'required|numeric'
        ]);

        if($validator->fails())
        {
            $message = 'Bad Request';
            $statuscode = 404;

            $errors = $validator->errors();
            foreach ($errors as $message) {
                array_push($error, $message);
            }
        } else {
            $news = new News();
            $news->title = $request->input('title');
            $news->content = $request->input('content');
            $news->category_id = $request->input('category_id');
            $news->save();
            if($news->save())
            {
                $data = $news;
            } else {
                $statuscode = 400;
                $message = 'Bad Request';
            }
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
        if($id){
            $validator = Validator::make($request->all(), [
                'title' => 'required|min:5',
                'content' => 'required|min:10',
                'category_id' => 'required|numeric'
            ]);

            if($validator->fails())
            {
                $message = 'Bad Request';
                $statuscode = 400;

                $error = $validator->errors();
                foreach ($error as $message) {
                    array_push($error, $message);
                }
            } else {
                $news = News::findOrFail($id);
                if($news->save()){
                    $statuscode = 200;
                    $data = $news;
                } else {
                    $statuscode = 400;
                    $message = 'Bad Request';
                }
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
            $news = News::findOrFail($id);
            if($news){
                if(!$news->delete()){
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
