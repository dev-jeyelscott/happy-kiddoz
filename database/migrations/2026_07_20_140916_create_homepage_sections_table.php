<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the fixed homepage section records.
     */
    public function up(): void
    {
        Schema::create('homepage_sections', function (Blueprint $table): void {
            $table->id();

            // Stores a backed HomepageSectionKey value.
            $table->string('key', 32)->unique();

            // Stores section-specific headings, images, cards, and other fields.
            $table->json('content');

            // Controls the section's position on the single-page homepage.
            $table->unsignedSmallInteger('sort_order');

            // Allows administrators to hide a section without deleting content.
            $table->boolean('is_visible')->default(true);

            $table->timestamps();

            // Supports the primary public query: visible sections in page order.
            $table->index(['is_visible', 'sort_order']);
        });
    }

    /**
     * Remove the homepage section table.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
