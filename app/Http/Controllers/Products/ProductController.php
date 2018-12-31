<?php

namespace App\Http\Controllers\Products;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Filtering\Filters\CategoryFilter;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductIndexResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('variations.stock')
            ->withFilters([
                'category' => new CategoryFilter(),
            ])
            ->paginate(10);

        return ProductIndexResource::collection($products);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product->load([
            'variations.type',
            'variations.stock',
            'variations.product',
        ]));
    }
}
