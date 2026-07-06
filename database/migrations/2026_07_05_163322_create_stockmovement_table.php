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
        Schema::create('stock_movement', function (Blueprint $table) {
            $table->string('stock_movement_id', 50)->primary();
            $table->string('product_id', 50);
            $table->integer('stock_quantity')->nullable();
            $table->string('batch_number', 100)->nullable();
            $table->timestamp('stock_date')->nullable();
            $table->string('stocked_type', 50)->nullable(); //stock taking, stock issued, stock returned
            $table->string('stocked_by', 150)->nullable();
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50)->nullable();
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
            $table->foreign('product_id')->references('product_id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movement');
    }
};
