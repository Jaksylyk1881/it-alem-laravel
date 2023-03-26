<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    private function auth(Request $request)
    {
        if (Auth::attempt($request->only(['phone', 'password']))) {
            $token = $request->user()->createToken('auth')->plainTextToken;
            $token = explode('|', $token)[1];
            $request->user()->update(['access_token' => $token]);
            $data = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'ttl' => 315569520,
                'user' => $request->user(),
            ];

            return $this->Result(200, $data);
        }
        return $this->Result(400);
    }

    public function login(Request  $request)
    {
        return $this->auth($request);
    }


    public function register(RegisterRequest $request)
    {
        $user_data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ];
        User::create($user_data);
        $auth_data = $this->auth($request);

        return $auth_data ?? $this->Result(400);
    }


}
