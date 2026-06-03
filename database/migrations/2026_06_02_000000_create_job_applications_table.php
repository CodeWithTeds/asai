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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_posting_id');
            $table->foreign('job_posting_id', 'fk_job_applications_job_posting_id')->references('id')->on('job_postings')->cascadeOnDelete();
            $table->index('job_posting_id');
            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone')->nullable();
            $table->string('residential_address')->nullable();
            $table->string('education_level')->nullable();
            $table->unsignedInteger('years_of_experience')->default(0);
            $table->boolean('has_license')->default(false);
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable();
            $table->unsignedInteger('height_cm')->nullable();
            $table->unsignedInteger('weight_kg')->nullable();
            $table->string('resume_path');
            $table->text('cover_letter')->nullable();
            $table->text('references')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
