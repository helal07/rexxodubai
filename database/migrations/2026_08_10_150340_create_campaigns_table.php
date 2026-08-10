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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Admin reference name
            $table->string('title')->nullable(); // Display title
            $table->string('subtitle')->nullable(); // Display subtitle
            $table->string('banner_image_url')->nullable();
            $table->string('button_text')->nullable()->default('DISCOVER');
            $table->string('button_link')->nullable()->default('/perfumes');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
