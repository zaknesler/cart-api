<?php

namespace App\Cart;

use App\Models\User;

class Cart
{
    /**
     * The specified to which the cart belongs.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new instance of a user's cart.
     *
     * @param \App\Models\User  $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Add products to the user's cart.
     *
     * @param array  $products
     */
    public function addProducts($products)
    {
        $this->user->cart()->syncWithoutDetaching(
            $this->getProductPayload($products)
        );
    }

    /**
     * Get the properly ordered array of products.
     *
     * @param  array  $products
     * @return array
     */
    protected function getProductPayload($products)
    {
        return collect($products)->keyBy('id')->map(function ($product) {
                return [
                    'quantity' => $product['quantity'] + $this->getCurrentQuantity($product['id']),
                ];
            })->toArray();
    }

    /**
     * Get the current cart quantity for a specified item.
     *
     * @param  int  $productId
     * @return int
     */
    protected function getCurrentQuantity($productId)
    {
        if ($product = $this->user->cart->where('id', $productId)->first()) {
            return $product->pivot->quantity;
        }

        return 0;
    }
}
