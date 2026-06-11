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
          $table->string('type_of_business', 300)->nullable();
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

        Schema::create('roles', function (Blueprint $table) {
          $table->string('role_id', 50)->primary();
          $table->string('tenant_id', 50);
          $table->string('role_name', 300);
          $table->string('role_description', 300)->nullable();
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
          $table->string('tenant_id', 50);
          $table->string('store_name', 300);
          $table->string('store_description', 300)->nullable();
          $table->string('type_of_business', 300)->nullable();
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
            // $table->foreign('store_id')->references('store_id')->on('stores');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id', 50)->primary();
            $table->string('firstname', 300);
            $table->string('othername', 300);
            $table->string('tenant_id', 50)->nullable();
            $table->string('store_id', 50)->nullable();
            $table->string('role_id', 50)->nullable();
            $table->string('email', 100)->unique();
            $table->string('telephone', 50)->unique()->nullable();
            $table->string('telephone_verify', 50)->default('No');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 150);
            $table->string('password_salt', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('added_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->string('archived', 100)->default('No')->index();
            $table->string('archived_by', 100)->nullable()->index();
            $table->timestamp('archived_date')->nullable()->index();
             // key
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants');
            $table->foreign('store_id')->references('store_id')->on('stores');
            $table->foreign('role_id')->references('role_id')->on('roles');
           
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id', 50)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();

            $table->foreign('user_id')->references('user_id')->on('users');
        });

        Schema::create('user_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->string('user_id', 50)->nullable();
            $table->string('logname',50)->nullable();
            $table->date('login_date')->nullable();
            $table->date('logout_date')->nullable();
            $table->timestamp('login_time')->nullable();
            $table->string('session_id',100)->nullable();
            $table->timestamp('logout_time')->nullable();
            $table->string('user_ip', 100)->nullable();
            $table->string('user_pc')->nullable();
            $table->string('tenant_id', 50);
            $table->string('store_id', 50);
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
            // $table->foreign('facility_id')->references('facility_id')->on('facility');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {      
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('user_logs');
        Schema::dropIfExists('roles');
    }
};
