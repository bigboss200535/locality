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
       Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_id')->unique();
            $table->string('claim_check_code')->nullable();
            $table->string('member_no')->index();
            $table->string('card_serial_no')->nullable();
            $table->string('surname');
            $table->string('other_names')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 1)->nullable();
            $table->string('hospital_rec_no')->nullable();
            $table->string('type_of_service')->nullable();
            $table->string('type_of_attendance')->nullable();
            $table->string('service_outcome')->nullable();
            $table->date('service_start_date')->nullable();
            $table->date('service_end_date')->nullable();
            $table->string('specialty_attended')->nullable();
            $table->string('principal_gdrg')->nullable();
            $table->integer('risk_score')->default(0);
            $table->enum('status', [
                'pending',
                'approved',
                'review',
                'flagged',
                'rejected'
            ])->default('pending');
            $table->json('fraud_flags')->nullable();
            $table->json('ai_response')->nullable();
            // $table->timestamps();
        });
              
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
