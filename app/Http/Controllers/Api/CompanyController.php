<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function show(User $company)
    {
        return $this->Result(
            200,
            User::with(['images', 'address'])
                ->withCount('reviews')
                ->withAvg('reviews', 'rate')
                ->where('id', $company->id)
                ->first()
        );
    }
}
