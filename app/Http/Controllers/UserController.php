<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function show($id)
    {
        if (!User::find($id)) {
            return response([
                'message' => 'User does not exist'
            ], 404);
        }
        $user = User::find($id);
        return $user;
    }

    public function update(Request $request, $id)
    {
        if (!$user_auth = $this->get_auth($request)) {
            return response([
                'message' => 'You have no auth'
            ], 401);
        }
        if ($user_auth->id != $id) {
            return response([
                'message' => 'You have no access'
            ], 403);
        } else {
            if (!$user  = User::find($id)) {
                return response([
                    'message' => 'User does not exist'
                ], 404);
            }

            $validated = $request->validate([
                'full_name' => 'string',
                'email' => 'string',
            ]);

            if ($user_auth->id == $id) {
                $user->update($validated);
                return $user;
            }
        }
    }

    public function destroy(Request $request, $id)
    {
        if (!$this->check_auth($request)) {
            return response([
                'message' => 'You have no auth'
            ], 401);
        } else if ($this->get_auth($request)->id != $id) {
            return response([
                'message' => 'You have no access'
            ], 403);
        } else {
            User::destroy($id);
            return response(['message' => 'Successfully deleted']);
        }
    }
}
