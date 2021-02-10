<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function registration(Request $request)
    {   
        $statuscode = 200;
        $message = 'success';
        $user = null;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if($validator->fails()){
            $error = [];
            $error = $validator->errors();
            foreach ($error as $message) {
                array_push($error, $message);
            }
            return response()->json([
                'statuscode' => 400,
                'message' => 'Bad Request',
                'data' => null,
                'error' => $error
            ]);
        }

        $input = $request->all();

        if(User::where('email', $input['email'])->first()){
            $statuscode = 400;
            $message = 'Email as ben registered';
        } else {
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
        }

        return response()->json([
            'statuscode' => $statuscode,
            'message' => $message,
            'data' => $user,
            'error' => null
        ], $statuscode);
    }

    public function login(Request $request)
    {
        $error = null;
        $message = 'success login';
        $statuscode = 200;
        $data = null;

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails())
        {
            $statuscode = 400;
            $error = [];
            $errors = $validator->errors();
            $message = 'Bad Request';
            foreach ($errors->all as $message) {
                array_push($error, $message);
            }

            return response()->json([
                'statuscode' => $statuscode,
                'message' => $message,
                'data' => $data,
                'error' => $error
            ], $statuscode);
        }

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            $accessToken = $user->createToken('login')->accessToken;
            return response()->json([
                'statuscode' => $statuscode,
                'message' => $message,
                'data' => $user,
                'token' => $accessToken,
                'error' => $error
            ]);
        } else {
            $statuscode = 401;
            $message = 'Authorized';

            return response()->json([
                'statuscode' => $statuscode,
                'message' => $message,
                'data' => $data,
                'error' => $error
            ], $statuscode);
        }
    }

    public function logout(Request $request)
    {
        $logout = $request->user()->token()->revoke();
        if($logout){
            return response()->json([
                'message' => 'Successfully logout'
            ]);
        }      
    }
}
