<?php

use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = factory(Product::class)->create([
            'name' => 'Coffee',
            'slug' => 'coffee',
            'description' => 'Some delicious coffee for you.',
            'price' => 1500,
        ]);

        $typeWhole = factory(ProductVariationType::class)->create([
            'name' => 'Whole bean',
        ]);

        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeWhole->id,
            'name' => '250g',
            'price' => 1000,
        ]);

        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeWhole->id,
            'name' => '500g',
            'price' => 1500,
        ]);


        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeWhole->id,
            'name' => '1kg',
            'price' => 3000,
        ]);

        // Ground

        $typeGround = factory(ProductVariationType::class)->create([
            'name' => 'Ground',
        ]);

        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeGround->id,
            'name' => '250g',
            'price' => 1000,
        ]);

        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeGround->id,
            'name' => '500g',
            'price' => 1500,
        ]);

        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeGround->id,
            'name' => '1kg',
            'price' => 3000,
        ]);
    }
}
