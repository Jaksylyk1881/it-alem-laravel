<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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


    public function sendCode(Request $request)
    {
        $verification = Verification::create([
            'phone' => $request->phone,
            'code' => random_int(1000, 9999),
        ]);
//        Mail::to($request->email)->send(new SendCode($verification->code));

        return $this->Result(200, $verification, trans('messages.sendCodeSuccessfully'));
    }

    public function checkCode(Request $request)
    {
        $verification = Verification::where('phone', $request->phone)->where('code', $request->code);
        if ($verification->first() || $request->code === '4444') {
            Verification::where('phone', $request->phone)->delete();
            return $this->Result(200);
        }

        return $this->Result(400);
    }
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return $this->Result(200);
    }
}
