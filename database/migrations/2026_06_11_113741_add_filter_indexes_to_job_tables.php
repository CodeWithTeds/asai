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
        // job_postings: admin filters by type and status individually
        Schema::table('job_postings', function (Blueprint $table) {
            $table->index('type');
        });

        // job_applications: admin filters by has_license, sorts by created_at
        Schema::table('job_applications', function (Blueprint $table) {
            $table->index('has_license');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropIndex(['type']);
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropIndex(['has_license']);
            $table->dropIndex(['created_at']);
        });
    }
};
