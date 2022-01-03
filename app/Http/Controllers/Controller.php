<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return a user authorization status.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function check_auth(Request $request) {
        if(!$request->header()){
            return false;
        } else if (!$request->header("Authorization") || $request->header("Authorization") == null){
            return false;
        } else if (!explode(" ", $request->header("Authorization"))[1]){
            return false;
        }
        $token = explode(" ", $request->header("Authorization"))[1];
        $user = User::where("token", $token)->first();
        if(!$user){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Return a authorized user.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_auth(Request $request) {
        if(!$request->header()){
            return null;
        } else if (!$request->header("Authorization")){
            return null;
        }
        $token = explode(" ", $request->header("Authorization"))[1];
        $user = User::where("token", $token)->first();
        if(!$user){
            return null;
        } else {
            return $user;
        }
    }
}
