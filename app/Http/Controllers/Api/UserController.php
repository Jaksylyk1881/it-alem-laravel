<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->Result(200, $request->user());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request)
    {
        $request->user()->update($request->except(['password', 'avatar']));
        if($request->password) {
            $request->user()->update(['password' => bcrypt($request->password)]);
        }
        if($request->avatar) {
            $request->user()->update(['avatar' => $this->uploadFile($request->avatar, 'users/avatars')]);
        }
        return $this->Result(200, $request->user());
    }

    public function products(Request $request)
    {
        $products = $request
            ->user()
            ->products()
            ->with([
                'images',
                'category',
            ])
            ->withCount('reviews')
            ->withCount('gifts')
            ->withAvg('reviews', 'rate')
            ->get()
            ->map(function ($product) {
                $product['has_gifts'] = $product['gifts_count'] > 0;
                return $product;
            });
        return $this->Result(200, [
            'products' => $products->where('category.type', 'product')->values(),
            'services' => $products->where('category.type', 'service')->values(),
        ]);
    }
}
