<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request  $request)
    {
//        if(Auth::attempt($request->all())) {
//            return Auth::user()->createToken('auth');
//        }
    }
}
