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
         Schema::create('tenants', function (Blueprint $table) {
          $table->string('tenant_id', 50)->primary();
          $table->string('tenant_name', 300);
          $table->string('tenant_description', 300)->nullable();
          $table->string('telephone', 50)->nullable();
          $table->string('location', 250)->nullable();
          $table->string('slogan', 250)->nullable();
          $table->string('email', 50)->index()->nullable();
          $table->string('type_of_business', 300)->nullable();
          $table->string('user_id', 50)->index();
          $table->timestamp('added_date')->nullable();
          $table->timestamp('updated_date')->nullable();
          $table->string('status', 50)->default('Active')->nullable();
          $table->string('added_by', 100)->nullable();
          $table->string('updated_by', 100)->nullable();
          $table->string('archived', 100)->default('No')->index();
          $table->string('archived_by', 100)->nullable()->index();
          $table->timestamp('archived_date')->nullable()->index();
        });

        Schema::create('roles', function (Blueprint $table) {
          $table->string('role_id', 50)->primary();
          $table->string('role_name', 300);
          $table->string('role_description', 300)->nullable();
          $table->string('usage', 10)->nullable();
          $table->string('user_id', 100)->nullable();
          $table->timestamp('added_date')->nullable();
          $table->timestamp('updated_date')->nullable();
          $table->string('status', 50)->default('Active')->nullable();
          $table->string('added_by', 100)->nullable();
          $table->string('updated_by', 100)->nullable();
          $table->string('archived', 100)->default('No')->index();
          $table->string('archived_by', 100)->nullable()->index();
          $table->timestamp('archived_date')->nullable()->index();
        });

        Schema::create('stores', function (Blueprint $table) {
          $table->string('store_id', 50)->primary();
          $table->string('store_name', 300);
          $table->string('tenant_id', 50);
          $table->string('telephone', 50)->nullable();
          $table->string('store_code', 50)->nullable();
          $table->string('store_description', 300)->nullable();
          $table->string('type_of_business', 300)->nullable();
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
            // $table->foreign('store_id')->references('store_id')->on('stores');
        });

         Schema::create('users', function (Blueprint $table) {
            $table->string('user_id', 50)->primary();
            $table->string('firstname', 300);
            $table->string('othername', 300);
            $table->string('tenant_id', 50)->nullable()->index();
            $table->string('store_id', 50)->nullable()->index();
            $table->string('role_id', 50)->nullable();
            $table->string('provider', 200)->nullable(); // e.g. Google, Facebook, etc.
            $table->string('provider_id', 200)->nullable(); // ID from the provider
            
            $table->text('avatar')->nullable(); // URL or base64 string for the user's avatar
            $table->string('email', 100)->unique();
            $table->string('blocked', 50)->default('No');
            $table->timestamp('last_login')->nullable();
            $table->string('telephone', 50)->unique()->nullable();
            $table->string('telephone_verify', 50)->default('No');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('usage', 20)->default('1');
            $table->string('password', 150);
            $table->string('user_no', 50)->nullable();
            $table->string('password_salt', 100)->nullable();
            $table->string('status', 100)->default('Active')->index();
            $table->timestamp('added_date')->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
             // keys
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
            $table->foreign('role_id')->references('role_id')->on('roles');
        });

      Schema::create('product_category', function (Blueprint $table) {
            $table->string('category_id', 50)->primary();
            $table->string('category_name', 150)->nullable();
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
            // $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->string('product_id', 50)->primary();
            $table->string('product_name', 150)->nullable();
            $table->string('product_type', 150)->nullable();// Variant
            $table->string('barcode', 150)->nullable();
            $table->string('qr_code', 150)->nullable();
            $table->string('supplier_id', 150)->nullable();
            $table->string('expirable', 50)->default('No'); //yes/no
            $table->string('stockable', 50)->default('No'); //yes/no
            $table->string('category_id', 50)->nullable();
            $table->string('store_id', 50)->nullable();
            $table->string('tenant_id', 50)->nullable();
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
            $table->foreign('category_id')->references('category_id')->on('product_category');
        });


         Schema::create('suppliers', function (Blueprint $table) {
          $table->string('supplier_id', 50)->primary();
          $table->string('supplier_name', 300);
          $table->string('telephone', 50)->nullable();
          $table->string('email', 150)->nullable();
          $table->string('tenant_id', 50);
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
        });

        Schema::create('purchase_order', function (Blueprint $table) {
          $table->string('purchase_order_id', 50)->primary();
          $table->string('order_no', 50)->nullable();
          $table->date('order_date')->nullable();
          $table->string('invoice_no', 50)->nullable();
          $table->float('total_value', 10, 2)->nullable();
          $table->float('discount', 10, 2)->nullable();
          $table->float('vat', 10, 2)->nullable();
          $table->string('supplier_id', 50);
          $table->string('order_status', 50)->default('Pending');
          $table->string('tenant_id', 50);
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
          $table->foreign('supplier_id')->references('supplier_id')->on('suppliers');
          $table->foreign('user_id')->references('user_id')->on('users');
        });

        Schema::create('purchase_order_details', function (Blueprint $table) {
          $table->string('purchase_order_details_id', 50)->primary();
          $table->string('purchase_order_id', 50);
        //   $table->string('invoice_no', 50)->nullable();
          $table->string('product_id', 50)->nullable();
          $table->string('quantity', 50)->nullable();
          $table->float('unit_price', 10, 2)->nullable();
          $table->float('total', 10, 2)->nullable();
          $table->string('order_status', 50)->default('Pending');
        //   $table->float('discount', 10, 2)->nullable();
        //   $table->float('vat', 10, 2)->nullable();
          $table->date('order_date')->nullable();
        //   $table->string('supplier_id', 50);
          $table->string('tenant_id', 50);
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
          $table->foreign('product_id')->references('product_id')->on('products');
          $table->foreign('store_id')->references('store_id')->on('stores');
          $table->foreign('purchase_order_id')->references('purchase_order_id')->on('purchase_order');
          $table->foreign('user_id')->references('user_id')->on('users');
        });

       

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id', 50)->nullable()->index();
            $table->string('ip_address', 50)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
            // keys
            $table->foreign('user_id')->references('user_id')->on('users');
        });

        Schema::create('user_logs', function (Blueprint $table) {
            $table->string('log_id')->primary();
            $table->string('user_id', 50)->nullable();
            $table->string('log_name',50)->nullable();
            $table->date('login_date')->nullable();
            $table->date('logout_date')->nullable();
            $table->timestamp('login_time')->nullable();
            $table->string('session_id',100)->nullable();
            $table->timestamp('logout_time')->nullable();
            $table->string('user_ip', 100)->nullable();
            $table->string('user_pc')->nullable();
            $table->string('tenant_id', 50)->nullable()->index();
            $table->string('store_id', 50)->nullable()->index();
            $table->string('added_id', 100)->nullable();
            $table->timestamp('added_date')->nullable();
            $table->string('status', 100)->default('Active')->index();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
            // key
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
        });

        Schema::create('user_permissions', function (Blueprint $table) {
            $table->string('user_id', 50)->primary();
            $table->string('module', 50);
            $table->string('delete', 50);
            $table->string('add', 50);
            $table->string('edit', 50);
            $table->string('read', 50);
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50)->nullable()->index();
            $table->string('added_id', 50)->nullable()->index();
            $table->string('password_salt', 100)->nullable();
            $table->string('status', 100)->default('Active')->index();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
             // keys
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
            $table->foreign('added_id')->references('user_id')->on('users');
        });

        Schema::create('requisition', function (Blueprint $table) {
          $table->string('requisition_id', 50)->primary();
          $table->string('requisition_no', 50)->nullable();
          $table->string('order_store_id', 50)->nullable();
          $table->string('issue_store_id', 50)->nullable();
          $table->date('requisition_date')->nullable();
          $table->float('unit_price', 10, 2)->nullable();
          $table->decimal('quantity', 10, 2)->nullable();
          $table->float('total_value', 10, 2)->nullable();
          $table->string('product_id', 50);
          $table->string('tenant_id', 50);
          $table->string('store_id', 50)->nullable();
          $table->string('requsition_status', 50)->nullable();//request/approved
          $table->string('user_id', 50)->nullable();
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
          $table->foreign('store_id')->references('store_id')->on('stores');
          $table->foreign('order_store_id')->references('store_id')->on('stores');
          $table->foreign('issue_store_id')->references('store_id')->on('stores');
          // $table->foreign('supplier_id')->references('supplier_id')->on('suppliers');
          $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {      
        Schema::dropIfExists('tenants');
         Schema::dropIfExists('stores');
        Schema::dropIfExists('users');
        Schema::dropIfExists('product_category');
        Schema::dropIfExists('products');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('user_logs');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_permissions');
        Schema::dropIfExists('supplier');
        Schema::dropIfExists('purchase_order');
        Schema::dropIfExists('purchase_order_details');
        
    }
};
