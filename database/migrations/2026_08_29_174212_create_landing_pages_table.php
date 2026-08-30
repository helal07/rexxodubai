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
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('hero_title')->nullable();
            $table->string('subtitle')->nullable();

            // Colors
            $table->string('theme_color')->nullable();
            $table->string('text_color')->nullable();
            $table->string('background_color')->nullable();
            $table->string('other_color')->nullable();

            // Buttons
            $table->string('primary_button_text')->nullable();
            $table->string('secondary_button_text')->nullable();

            // Video
            $table->string('youtube_video_url')->nullable();

            // JSON Sections
            $table->json('features')->nullable(); // Titles & Texts
            $table->json('feature_images')->nullable();
            $table->json('why_choose_us')->nullable(); // 4 Items
            $table->json('media_banners')->nullable(); // 2 Banners
            $table->json('reviews')->nullable(); // Reviews array & Title
            $table->json('gallery_images')->nullable(); // 8 Images
            $table->json('faqs')->nullable(); // 3 FAQs

            // Descriptions
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();

            // Settings
            $table->string('homepage_product_title')->nullable();
            $table->boolean('show_product_section')->default(true);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // Pivot table for products
        Schema::create('landing_page_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_product');
        Schema::dropIfExists('landing_pages');
    }
};
