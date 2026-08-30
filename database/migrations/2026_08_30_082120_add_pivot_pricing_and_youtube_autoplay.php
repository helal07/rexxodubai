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
        Schema::table('landing_page_product', function (Blueprint $table) {
            $table->decimal('regular_price', 10, 2)->nullable()->after('product_id');
            $table->decimal('offer_price', 10, 2)->nullable()->after('regular_price');
        });

        Schema::table('landing_pages', function (Blueprint $table) {
            $table->boolean('youtube_autoplay')->default(true)->after('youtube_video_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_product', function (Blueprint $table) {
            $table->dropColumn(['regular_price', 'offer_price']);
        });

        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn('youtube_autoplay');
        });
    }
};
