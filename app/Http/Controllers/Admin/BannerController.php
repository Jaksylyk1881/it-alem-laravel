<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::query()->orderByDesc('id')->paginate();
        $categories = Category::all();

        return view('admin.pages.banners', compact([
            'banners',
            'categories',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $banner = new Banner($request->except('image'));
        $banner->image = $this->uploadFile($request->image, 'banners');
        $banner->save();

        return back()->withSuccess('Успешно');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $banner->update($request->except('image'));
        if($request->image) {
            $banner->update(['image' => $this->uploadFile($request->image, 'banners')]);
        }
        return back()->withSuccess('Успешно');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();
        return back()->withSuccess('Успешно');
    }

}
