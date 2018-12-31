<?php

use App\Models\Stock;
use App\Models\Product;
use App\Models\Category;
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
        $this->coffee();
        $this->shirt();

        ProductVariation::get()->each(function ($variation) {
            factory(Stock::class)->create([
                'product_variation_id' => $variation->id,
                'quantity' => 10,
            ]);
        });
    }

    private function coffee()
    {
        $category = factory(Category::class)->create([
            'name' => 'Food',
            'slug' => 'food',
        ]);

        $category->products()->save(
            $product = factory(Product::class)->create([
                'name' => 'Coffee',
                'slug' => 'coffee',
                'description' => 'Some delicious coffee for you.',
                'price' => 1000,
            ])
        );

        $typeWhole = factory(ProductVariationType::class)->create([
            'name' => 'Whole bean',
        ]);

        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeWhole->id,
            'name' => '250g',
            'price' => null,
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

        $typeGround = factory(ProductVariationType::class)->create([
            'name' => 'Ground',
        ]);

        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeGround->id,
            'name' => '250g',
            'price' => null,
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

        $category->products()->saveMany(Product::get());
    }

    private function shirt()
    {
        $category = factory(Category::class)->create([
            'name' => 'Clothing',
            'slug' => 'clothing',
        ]);

        $category->products()->save(
            $product = factory(Product::class)->create([
                'name' => 'T-shirt',
                'slug' => 'tshirt',
                'description' => 'Very comfortable t-shirts.',
                'price' => 4500,
            ])
        );

        $typeBlue = factory(ProductVariationType::class)->create([
            'name' => 'Blue',
        ]);

        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeBlue->id,
            'name' => 'small',
            'price' => null,
        ]);

        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeBlue->id,
            'name' => 'medium',
            'price' => 5000,
        ]);


        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeBlue->id,
            'name' => 'large',
            'price' => 5500,
        ]);

        $typeRed = factory(ProductVariationType::class)->create([
            'name' => 'Red',
        ]);

        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeRed->id,
            'name' => 'small',
            'price' => null,
        ]);

        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeRed->id,
            'name' => 'medium',
            'price' => 5000,
        ]);

        factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $typeRed->id,
            'name' => 'large',
            'price' => 5500,
        ]);
    }
}
