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
        
       
            
        
        Schema::create('product_prices', function (Blueprint $table) {
            $table->string('product_id', 50);
            $table->float('unit_cost')->nullable();
            $table->float('unit_price')->nullable();
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50);
            $table->string('user_id', 50)->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('status', 50)->default('Active')->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
                //key 
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
            $table->foreign('user_id')->references('user_id')->on('users');
        });
            

        Schema::create('product_stocked', function (Blueprint $table) {
            $table->string('stock_id', 50)->primary();
            $table->string('product_id', 50);
            $table->integer('stock_quantity')->nullable();
            $table->string('batch_number', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('stock_date')->nullable();
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50);
            $table->string('user_id', 50)->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('status', 50)->default('Active')->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
            // key
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
            $table->foreign('user_id')->references('user_id')->on('users');
        });

        //  Schema::create('stocked_logs', function (Blueprint $table) {
        //     $table->string('stock_id', 50)->primary();
        //     $table->string('product_id', 50);
        //     $table->integer('stock_quantity')->nullable();
        //     $table->string('batch_number', 100)->nullable();
        //     $table->date('expiry_date')->nullable();
        //     $table->timestamp('stock_date')->nullable();
        //     $table->string('tenant_id', 50)->nullable();
        //     $table->string('store_id', 50);
        //     $table->string('user_id', 50)->nullable();
        //     $table->timestamp('added_date')->nullable();
        //     $table->timestamp('updated_date')->nullable();
        //     $table->string('status', 50)->default('Active')->nullable();
        //     $table->string('added_by', 100)->nullable();
        //     $table->string('updated_by', 100)->nullable();
        //     $table->string('archived', 100)->default('No')->index();
        //     $table->string('archived_by', 100)->nullable()->index();
        //     $table->timestamp('archived_date')->nullable()->index();
        //     // key
        //     $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
        //     $table->foreign('store_id')->references('store_id')->on('stores');
        //     $table->foreign('user_id')->references('user_id')->on('users');
        // });

        Schema::create('bills_payment', function (Blueprint $table) {
            $table->string('payment_id', 50)->primary();
            $table->float('total_payment');
            $table->string('service_payment', 50);
            $table->string('product_payment', 50);
            $table->integer('total_levies')->nullable();
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('item_discount', 10, 2)->default(0);
            $table->decimal('cart_discount', 10, 2)->default(0);
            $table->string('cart_discount_type')->nullable();
            $table->decimal('cart_discount_value', 10, 2)->default(0);
            $table->decimal('total_discount', 10, 2)->default(0);
            $table->string('receipt_number', 50);
            $table->string('payment_method', 50)->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->string('user_id', 50)->nullable();
            $table->string('expirable', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('status', 50)->default('Active')->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
                // key
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
            $table->foreign('user_id')->references('user_id')->on('users');
        });

         Schema::create('receipts', function (Blueprint $table) {
            $table->string('receipt_id', 50)->primary();
            $table->timestamp('receipt_date')->index();
            $table->string('receipt_number', 50);
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50);
            $table->timestamp('transaction_time')->nullable();
            $table->string('user_id', 50)->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('status', 50)->default('Active')->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
                // key
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
            $table->foreign('user_id')->references('user_id')->on('users');
        });
        
        Schema::create('product_sales', function (Blueprint $table) {
            $table->string('sales_id', 50)->primary();
            $table->string('product_id', 50);
            $table->string('payment_id', 50);
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->string('receipt_number', 50)->index();
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50);
            $table->string('refunded', 50)->default('No')->nullable();
            $table->string('refunded_user_id', 50)->nullable();
            $table->timestamp('refunded_dated')->nullable();
            $table->integer('quantity')->nullable();
            $table->float('unit_cost')->nullable();
            $table->float('total')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->string('user_id', 50)->nullable();
            // $table->string('expirable', 100)->nullable();
            // $table->date('expiry_date')->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('status', 50)->default('Active')->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
             // key
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
            $table->foreign('user_id')->references('user_id')->on('users');
              $table->foreign('payment_id')->references('payment_id')->on('bills_payment');
        });

        Schema::create('refunds', function (Blueprint $table) {
            $table->string('refund_id', 50)->primary();
            $table->string('payment_receipt', 50);
            $table->string('product_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->float('amount_refunded', 10, 2)->nullable();
            $table->text('comments')->nullable();
            $table->string('user_id', 50)->nullable();
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50);
            $table->string('expirable', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('status', 50)->default('Active')->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
                // keys
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
            $table->foreign('user_id')->references('user_id')->on('users');
        });

        // Schema::create('product_management', function (Blueprint $table) {
        //     $table->string('management_id', 50)->primary();
        //     $table->string('batch_id', 50)->nullable();
        //     $table->string('store_id', 50)->nullable();
        //     $table->integer('quantity')->nullable();
        //     $table->string('reason', 50)->nullable(); //expired, damaged, lost, stolen
        //     $table->string('tenant_id', 50)->nullable();
        //     $table->string('user_id', 50)->nullable();
        //     $table->string('expirable', 100)->nullable();
        //     $table->date('expiry_date')->nullable();
        //     $table->text('comments')->nullable();
        //     $table->timestamp('added_date')->nullable();
        //     $table->timestamp('updated_date')->nullable();
        //     $table->string('status', 50)->default('Active')->nullable();
        //     $table->string('added_by', 100)->nullable();
        //     $table->string('updated_by', 100)->nullable();
        //     $table->string('archived', 100)->default('No')->index();
        //     $table->string('archived_by', 100)->nullable()->index();
        //     $table->timestamp('archived_date')->nullable()->index();
        //      // key
        //     $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
        //     $table->foreign('store_id')->references('store_id')->on('stores');
        //      $table->foreign('user_id')->references('user_id')->on('users');
        // });

        Schema::create('product_management', function (Blueprint $table) {
            $table->string('product_management_id', 50)->primary();
            $table->string('product_id', 50); 
            $table->string('batch_id', 50)->nullable(); //if expired, add batch
            $table->string('store_id', 50)->nullable();
            $table->integer('unit_cost')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('reason', 50)->nullable(); //expired, damaged, lost, stolen
            $table->string('tenant_id', 50)->nullable();
            $table->string('user_id', 50)->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('spoiled_date')->nullable();
            $table->text('comments')->nullable();
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('status', 50)->default('Active')->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
             // key
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('store_id')->references('store_id')->on('stores');
            $table->foreign('user_id')->references('user_id')->on('users');
        });

        Schema::create('stock_taking', function (Blueprint $table) {
            $table->string('stocking_id', 50)->primary();
            $table->string('product_id', 50);
            $table->integer('product_qty')->nullable();
            $table->string('store_id', 50);
            $table->string('tenant_id', 50)->nullable();
            $table->string('user_id', 50)->nullable();
            $table->string('status', 50)->default('Active')->nullable();
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
            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocked');
        Schema::dropIfExists('bills_payment');
        Schema::dropIfExists('product_sales');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('product_management');
        Schema::dropIfExists('stock_taking');
        Schema::dropIfExists('receipts'); 
    }
};
