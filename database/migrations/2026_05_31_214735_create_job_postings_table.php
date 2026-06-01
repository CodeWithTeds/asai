<?php

use App\Enums\JobPostingStatus;
use App\Enums\JobPostingType;
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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete()->index();
            $table->string('title');
            $table->text('description');
            $table->string('cover_image')->nullable();
            $table->string('location')->nullable();
            $table->string('type')->default(JobPostingType::FullTime->value);
            $table->string('status')->default(JobPostingStatus::Open->value);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Optimize the active scope query
            $table->index(['status', 'starts_at', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
