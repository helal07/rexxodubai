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
        Schema::create('frontend_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section')->index(); // 'home_campaign', 'footer', 'global'
            $table->string('key'); // 'banner_image', 'about_text', 'site_logo'
            $table->string('type')->default('text'); // 'text', 'image', 'video', 'json'
            $table->longText('value')->nullable();
            $table->boolean('is_file')->default(false);
            $table->timestamps();

            $table->unique(['section', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frontend_contents');
    }
};
