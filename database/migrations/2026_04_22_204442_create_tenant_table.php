<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_category', function (Blueprint $table) {
            $table->string('category_id', 50)->primary();
            $table->string('category_name', 150)->nullable();
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50);
            $table->string('user_id', 100)->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('status', 100)->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();

            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
            // $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->string('product_id', 50)->primary();
            $table->string('product_name', 150)->nullable();
            $table->string('product_type', 150)->nullable();
            $table->string('category_id', 50)->nullable();
            $table->string('tenant_id', 50)->nullable();
            $table->string('expirable', 50)->nullable(); //yes/no
            $table->string('store_id', 50);
            $table->string('user_id', 100)->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('status', 100)->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
            // key
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
        });

        Schema::create('product_prices', function (Blueprint $table) {
            $table->string('product_id', 50);
            $table->float('unit_cost')->nullable();
            $table->float('unit_price')->nullable();
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50);
            $table->string('user_id', 100)->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('status', 100)->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
                //key 
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
        });
            

        Schema::create('product_stocked', function (Blueprint $table) {
            $table->string('stock_id', 50)->primary();
            $table->string('product_id', 50);
            $table->integer('stock_quantity')->nullable();
            $table->timestamp('stock_date')->nullable();
            $table->string('stocked_by', 100)->nullable();
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50);
            $table->string('user_id', 100)->nullable();
            $table->string('expirable', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('status', 100)->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
                // key
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
        });

        Schema::create('bills_payment', function (Blueprint $table) {
            $table->string('payment_id', 50)->primary();
            $table->float('total_payment');
            $table->string('service_payment', 50);
            $table->string('product_payment', 50);
            $table->integer('total_levies')->nullable();
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50);
            $table->timestamp('transaction_time')->nullable();
            $table->string('user_id', 100)->nullable();
            $table->string('expirable', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('tenant_status', 100)->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
                // key
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
        });

        Schema::create('product_sales', function (Blueprint $table) {
            $table->string('sales_id', 50)->primary();
            $table->string('product_id', 50);
            $table->string('payment_id', 50);
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50);
            $table->integer('quantity')->nullable();
            $table->float('unit_cost')->nullable();
            $table->float('total')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->string('user_id', 100)->nullable();
            $table->string('expirable', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('tenant_status', 100)->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
                // key
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
        });

        Schema::create('expiry_management_details', function (Blueprint $table) {
            // $table->string('expiry_id', 50)->primary();
            $table->string('batch_id', 50);
            $table->string('product_id')->nullable();
            $table->integer('stocked_qty')->nullable();
            // $table->integer('expiry_date')->nullable();
            $table->text('comments')->nullable();
            $table->string('user_id', 100)->nullable();
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50);
            $table->string('expirable', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('tenant_status', 100)->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
                // keys
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
        });

        Schema::create('product_expiry_management', function (Blueprint $table) {
            $table->string('expiry_id', 50)->primary();
            $table->string('batch_id', 50);
            $table->text('comments')->nullable();
            $table->string('store_id', 50);
            $table->string('tenant_id', 50)->nullable();
            $table->string('user_id', 100)->nullable();
            $table->string('expirable', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('tenant_status', 100)->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
             // key
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
        });

        Schema::create('stock_taking', function (Blueprint $table) {
            $table->string('stocking_id', 50)->primary();
            $table->string('product_id', 50);
            $table->integer('product_qty')->nullable();
            $table->string('store_id', 50);
            $table->string('tenant_id', 50)->nullable();
            $table->string('user_id', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('approved_by')->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('tenant_status', 100)->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
             // key
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_category');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_stocked');
        Schema::dropIfExists('bills_payment');
        Schema::dropIfExists('product_sales');
        Schema::dropIfExists('expiry_management_details');
        Schema::dropIfExists('product_expiry_management');
        Schema::dropIfExists('stock_taking');
        
        
         
    }
};
