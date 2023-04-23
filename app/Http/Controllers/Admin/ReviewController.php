<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $reviews = $product->reviews()->paginate();
        return view('admin.pages.reviews', compact([
            'reviews',
        ]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product, Review $review)
    {
        $review->delete();
        return back()->withSuccess('Успешно');
    }

}
