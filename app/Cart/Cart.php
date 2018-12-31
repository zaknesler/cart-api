<?php

namespace App\Cart;

use App\Cart\Money;
use App\Models\User;
use App\Models\ShippingMethod;

class Cart
{
    /**
     * The specified to which the cart belongs.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Has the maximum quantity of a product changed?
     *
     * @var boolean
     */
    protected $changed;

    /**
     * The identifier for a shipping method.
     *
     * @var \App\Models\ShippingMethod
     */
    protected $shippingMethod;

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
     * @param  array  $products
     * @return void
     */
    public function add($products)
    {
        $this->user->cart()->syncWithoutDetaching(
            $this->getProductPayload($products)
        );
    }

    /**
     * Update the quantity of a product in the user's cart.
     *
     * @param  int  $productId
     * @param  int  $quantity
     * @return void
     */
    public function update($productId, $quantity)
    {
        $this->user->cart()->updateExistingPivot($productId, [
            'quantity' => $quantity,
        ]);
    }

    /**
     * Delete a product from the user's cart.
     *
     * @param  int  $productId
     * @return void
     */
    public function delete($productId)
    {
        $this->user->cart()->detach($productId);
    }

    /**
     * Sync the quantity of an item in the cart with the actual stock.
     *
     * @return void
     */
    public function sync()
    {
        $this->user->cart->each(function ($product) {
            $quantity = $product->minStock($product->pivot->quantity);

            $this->changed = $quantity != $product->pivot->quantity;

            $product->pivot->update([
                'quantity' => $quantity,
            ]);
        });
    }

    /**
     * Empty all products from a user's cart.
     *
     * @return void
     */
    public function empty()
    {
        $this->user->cart()->detach();
    }

    /**
     * Attach a specified shipping method to the cart.
     *
     * @param  int  $shippingMethod
     * @return self
     */
    public function withShippingMethod($shippingMethodId)
    {
        $this->shippingMethod = ShippingMethod::find($shippingMethodId);

        return $this;
    }

    /**
     * Determine if a user's cart is empty.
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->user->cart->sum('pivot.quantity') === 0;
    }

    /**
     * Fetch the subtotal of all products in a user's cart.
     *
     * @return \App\Cart\Money
     */
    public function subtotal()
    {
        $subtotal = $this->user->cart->sum(function ($product) {
            return $product->price->amount() * $product->pivot->quantity;
        });

        return new Money($subtotal);
    }

    /**
     * Fetch the total of all products and additional fees.
     *
     * @return \App\Cart\Money
     */
    public function total()
    {
        if (!$this->isEmpty() && $this->shippingMethod) {
            return $this->subtotal()->add($this->shippingMethod->price);
        }

        return $this->subtotal();
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

    /**
     * Determine if the quantity of a product in the cart has changed.
     *
     * @return boolean
     */
    public function hasChanged()
    {
        return $this->changed;
    }
}
