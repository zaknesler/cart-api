<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariationStockView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS product_variation_stock_view");

        DB::statement("
            CREATE VIEW product_variation_stock_view AS
            SELECT
                product_variations.product_id AS product_id,
                product_variations.id AS product_variation_id,
                COALESCE(SUM(stocks.quantity) - COALESCE(SUM(order_product_variation.quantity), 0), 0) AS stock,
                CASE WHEN COALESCE(SUM(stocks.quantity) - COALESCE(SUM(order_product_variation.quantity), 0), 0) > 0
                    THEN 1
                    ELSE 0
                END in_stock
            FROM product_variations
            LEFT JOIN (
                SELECT stocks.product_variation_id AS id, SUM(stocks.quantity) AS quantity
                FROM stocks
                GROUP BY stocks.product_variation_id
            ) AS stocks USING (id)
            LEFT JOIN (
                SELECT
                    order_product_variation.product_variation_id AS id,
                    SUM(order_product_variation.quantity) AS quantity
                FROM order_product_variation
                GROUP BY order_product_variation.product_variation_id
            ) AS order_product_variation USING (id)
            GROUP BY product_variations.id, product_variations.product_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS product_variation_stock_view");
    }
}
