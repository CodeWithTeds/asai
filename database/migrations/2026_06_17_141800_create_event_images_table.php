<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the event_images table
        Schema::create('event_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('image_path');
            $table->timestamps();
        });

        // Migrate existing single images to the new table
        $events = DB::table('events')->whereNotNull('image')->get();
        foreach ($events as $event) {
            DB::table('event_images')->insert([
                'event_id' => $event->id,
                'image_path' => $event->image,
                'created_at' => $event->created_at ?? now(),
                'updated_at' => $event->updated_at ?? now(),
            ]);
        }

        // Drop the old single image column
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add the image column
        Schema::table('events', function (Blueprint $table) {
            $table->string('image')->nullable()->after('type');
        });

        // Populate it with the first image from event_images
        $images = DB::table('event_images')->orderBy('id')->get()->groupBy('event_id');
        foreach ($images as $eventId => $eventImages) {
            DB::table('events')->where('id', $eventId)->update([
                'image' => $eventImages[0]->image_path,
            ]);
        }

        // Drop the event_images table
        Schema::dropIfExists('event_images');
    }
};
