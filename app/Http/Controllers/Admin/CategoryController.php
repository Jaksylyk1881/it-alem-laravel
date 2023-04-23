<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()->orderByDesc('id')->paginate();

        return view('admin.pages.categories', compact([
            'categories',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = new Category($request->except('image'));
        $category->image = $this->uploadFile($request->image, 'categories');
        $category->save();

        return back()->withSuccess('Успешно');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $category->update($request->except('image'));
        if($request->image) {
            $category->update(['image' => $this->uploadFile($request->image, 'categories')]);
        }
        return back()->withSuccess('Успешно');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return back()->withSuccess('Успешно');
    }

}
