<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\City;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()->orderByDesc('id')->paginate();
        $addresses = Address::all();
        $cities = City::all();
        return view('admin.pages.users', compact([
            'users',
            'addresses',
            'cities',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new User($request->except([
                'avatar',
                'images',
                'address',
                'password',
            ]));

        if($request->has('avatar')) {
            $user->avatar = $this->uploadFile($request->avatar, 'users/avatars');
        }
        if($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        if ($request->address['email']) {
            $address_data = $request->address;
            $address_data['name'] = $request->name;
            $address_data['phone'] = $request->phone;
            $address = $user->addresses()->create($address_data);
            $user->address_id = $address->id;
        }

        $this->storeImages($user, $request->images);
        $user->save();

        return back()->withSuccess('Успешно');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->except([
            'avatar',
            'images',
            'password',
        ]));

        if($request->has('avatar')) {
            $user->avatar = $this->uploadFile($request->avatar, 'users/avatars');
        }
        if($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        $this->storeImages($user, $request->images);
        $user->save();

        return back()->withSuccess('Успешно');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back()->withSuccess('Успешно');
    }

    private function storeImages(User $user, $images)
    {
        foreach ($images ?? [] as $image) {
            Image::create([
                'user_id' => $user->id,
                'path' => $this->uploadFile($image, 'products/images')
            ]);
        }
    }
    public function destroyImage(Image $image)
    {
        $image->delete();
        return back()->withSuccess('Успешно');
    }
}
