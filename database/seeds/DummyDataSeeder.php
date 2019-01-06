<?php

use App\Models\User;
use App\Models\Stock;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\ShippingMethod;
use App\Models\CountryDivision;
use Illuminate\Database\Seeder;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;

class DummyDataSeeder extends Seeder
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
        $this->productStocks();
        $this->shippingMethods();
        $this->user();
    }

    private function user()
    {
        $user = User::create([
            'name' => 'Zak Nesler',
            'email' => 'zaknesler@gmail.com',
            'password' => 'secret',
        ]);

        $user->addresses()->create([
            'default' => true,
            'name' => 'Zak Nesler',
            'address_1' => '123 Sunnyside Lane',
            'city' => 'Fakeville',
            'postal_code' => '12345',
            'country_id' => Country::where('code', 'US')->first()->id,
            'country_division_id' => CountryDivision::where('code', 'PA')->first()->id,
        ]);

        $user->addresses()->create([
            'default' => false,
            'name' => 'Zak Nesler',
            'address_1' => '456 Wonderful Avenue',
            'city' => 'Unrealestate',
            'postal_code' => '90210',
            'country_id' => Country::where('code', 'US')->first()->id,
            'country_division_id' => CountryDivision::where('code', 'PA')->first()->id,
        ]);

        $user->paymentMethods()->create([
            'default' => true,
            'card_type' => 'Visa',
            'last_four' => '4242',
            'provider_id' => str_random(32),
        ]);

        $user->paymentMethods()->create([
            'default' => false,
            'card_type' => 'MasterCard',
            'last_four' => '1234',
            'provider_id' => str_random(32),
        ]);
    }

    private function productStocks()
    {
        ProductVariation::get()->each(function ($variation) {
            factory(Stock::class)->create([
                'product_variation_id' => $variation->id,
                'quantity' => 10,
            ]);
        });
    }

    private function shippingMethods()
    {
        Country::where('code', 'US')->first()->shippingMethods()->saveMany([
            factory(ShippingMethod::class)->create([
                'name' => 'UPS',
                'price' => 1000,
            ]),
            factory(ShippingMethod::class)->create([
                'name' => 'UPS Business',
                'price' => 2000,
            ]),
            factory(ShippingMethod::class)->create([
                'name' => 'USPS First-class',
                'price' => 2500,
            ]),
            factory(ShippingMethod::class)->create([
                'name' => 'USPS Next-day',
                'price' => 3000,
            ]),
        ]);
    }

    private function coffee()
    {
        $categoryParent = factory(Category::class)->create([
            'name' => 'Food',
            'slug' => 'food',
        ]);

        $category = factory(Category::class)->create([
            'name' => 'Coffee',
            'slug' => 'coffee',
            'parent_id' => $categoryParent->id,
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
        $categoryParent = factory(Category::class)->create([
            'name' => 'Clothing',
            'slug' => 'clothing',
        ]);

        $category = factory(Category::class)->create([
            'name' => 'T-Shirts',
            'slug' => 'tshirts',
            'parent_id' => $categoryParent->id,
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
