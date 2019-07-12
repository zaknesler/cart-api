<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the ordered categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return CategoryResource::collection(
            Category::parents()->ordered()->with('children')->get()
        );
    }
}
