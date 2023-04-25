<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->get();
        return $this->Result(200, [
            'products' => $categories->where('type', 'product')->values(),
            'services' => $categories->where('type', 'service')->values(),
        ]);
    }
}
