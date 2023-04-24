<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'phone' => 'required',
            'password' => 'required|string|min:3',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) return redirect()->back()->withErrors('Ошибка валидаций');

        $user = User::where( 'phone', $request['phone'])->first();

        if (!$user) return redirect()->back()->withErrors('Ошибка, пользователь не найден');
        if (!Hash::check($request['password'], $user->password)) return redirect()->back()->withErrors(trans('auth.failed'));
        if ($user->is_admin === 1) {
            if (Auth::attempt($request->only(['phone', 'password']))) {
                return redirect()->route('admin.home');
            }
        }
        return redirect()->back()->withErrors('Доступ закрыт');
    }


    function logout (Request $request)
    {
        Auth::logout();
        return redirect()->route('admin.auth');
    }

    public function home()
    {
        $product_count = Product::count();
        $user_count = User::count();
        $order_count = Product::count();
        return view('admin.pages.home', compact([
            'product_count',
            'user_count',
            'order_count',
        ]));
    }
}
