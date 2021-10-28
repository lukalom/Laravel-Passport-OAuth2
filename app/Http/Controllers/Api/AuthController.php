<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthController extends Controller
{   

    // REGISTER - POST
    public function register(Request $request)
    {
        // validation
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:authors",
            "password" => "required|confirmed",
            "phone_no" => "required",
            
        ]);

        // create data
        $author = new Author();

        $author->name = $request->name; 
        $author->email = $request->email;
        $author->password = bcrypt($request->password);
        $author->phone_no = $request->phone_no;

        // save data and send response
        $author->save();

        return response()->json([
            "status" => 1,
            "message" => "Author created successfuly"
        ], 200);
    }

    // LOGIN - POST 
    public function login(Request $request)
    {
        // validation
        $login_data = $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        // validate auth data
        if (!auth()->attempt($login_data)) {
            return response()->json([
                "status" => false,
                "message" => "Invalid credentials"
            ]);
        }

        // token
        $token = auth()->user()->createToken("auth_token")->accessToken;

        // send response
        return response()->json([
            "status" => true,
            "message" => "Auther Logged in successfuly",
            "access_token" => $token
        ]);
        
    }

    // PROFILE - GET
    public function profile()
    {
        $user_data = auth()->user();
         
        return response()->json([
            "status" => true,
            "message" => "User data",
            "data" => $user_data
        ]);
    }

    // LOGOUT - POST
    public function logout(Request $request)
    {   
        // get token value
        $token = $request->user()->token();

        // revoke this token value
        $token->revoke();

        return response()->json([
            "status" => true,
            "message" => "Author Logged out successfuly"
        ]);
    }
}
