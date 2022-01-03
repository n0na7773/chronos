<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Calendar;
use App\Models\User_calendar;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated =  $request->validate([
            'login'=> 'required|string|unique:users,login',
            'full_name'=> 'required|string',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|confirmed|min:4'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $calendar = Calendar::create([
            'title' => 'Main calendar',
            'user_id' => $user->id,
            'main' => true,
        ]);

        User_calendar::create([
            'user_id' => $user->id,
            'calendar_id' => $calendar->id,
        ]);

        return response([
            'message' => 'User registered. Please log in',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'=> 'required|string',
            'password'=> 'required|string|min:4'
        ]);

        if ($token = JWTAuth::attempt($credentials)) {
            $user = JWTAuth::user();
            $user->token = $token;
            $user->save();

            return response([
                'message' => 'Logged in',
                'token' => $token,
                'user' => $user,
            ]);
        } else {
            return response([
                'message' => 'Incorrect log in!'
                ], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = auth()->user();
            if($user){
                JWTAuth::invalidate(JWTAuth::getToken());
                $user = User::find($user->id);
                $user->token = NULL;
                $user->save();
                return response(['message' => 'Successfully logged out'], 200);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response(['error' => $e->getMessage()], 401);
        }
    }
}
