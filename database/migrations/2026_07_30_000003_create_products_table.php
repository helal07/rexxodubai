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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('scent_family')->nullable(); // e.g. Woody Floral, Amber Spice, Fresh Citrus
            $table->string('concentration')->default('Eau de Parfum'); // Eau de Parfum, Parfum, Eau de Toilette
            $table->json('sizes')->nullable(); // e.g. ["50ml", "100ml"]
            $table->decimal('price', 10, 2);
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('notes_top')->nullable();
            $table->string('notes_heart')->nullable();
            $table->string('notes_base')->nullable();
            $table->string('primary_image_url')->nullable();
            $table->string('secondary_image_url')->nullable(); // Cross-fade hover shot
            $table->enum('gender', ['women', 'men', 'unisex'])->default('unisex');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->integer('stock')->default(50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
