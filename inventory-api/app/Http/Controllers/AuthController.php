<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //Register

    public function register(Request $request){
        $attrs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => $attrs['password'],
        ]);

        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ]);
    }

    // Login

    public function login(Request $request){
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => $attrs['password'],
        ]);

        //attempt login
        if(!Auth::attempt($attrs))
        {
            return response([
                'message' => 'Invalid Credentials'
            ], 403);
        }

        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);

    }

    //logout user

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout Successful'
        ], 200);

    }

    //Fetch User Info

    public function show(Request $request){
        return response([
            'user' => auth()->user()
        ], 200);
    }

    public function update(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string',
        ]);

        $image = $this->saveImage($request->img, 'profiles');

        auth()->user()->update([
            'name' => $attrs['name'],
            'image' => $image
        ]);

        return response([
            'message' => 'User Profile Updated',
            'user' => auth()->user()
        ], 200);





    }
}
