<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class UserReviewController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        return $this->Result(200, Review::create(array_merge($request->all(), [
            'user_id' => $request->user()->id,
        ])));
    }
}
