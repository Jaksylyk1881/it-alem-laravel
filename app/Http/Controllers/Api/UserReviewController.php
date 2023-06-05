<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Review\UserReviewCompanyStoreRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class UserReviewController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index($product)
    {
        $comments = Review::query()
            ->with('user')
            ->where('product_id', $product)
            ->orderByDesc('id')
            ->get();
        $statistics = $comments->countBy('rate');
        $res = [
            'statistics' => [
                'by_rate' => $statistics->isEmpty() ? null : $statistics,
                'avg' => $comments->avg('rate'),
                'count' => $comments->count(),
            ],
            'items' => $comments,
        ];
        return $this->Result(200, $res);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(UserReviewCompanyStoreRequest $request, Product $product)
    {
        return $this->Result(200, $product->reviews()->create([
            'rate' => $request->rate,
            'body' => $request->body,
            'user_id' => $request->user()->id,
        ]));
    }

    public function companyStore(UserReviewCompanyStoreRequest $request, $company)
    {
        return $this->Result(200, Review::query()->create([
            'rate' => $request->rate,
            'body' => $request->body,
            'user_id' => $request->user()->id,
            'company_id' => $company,
        ]));
    }
}
