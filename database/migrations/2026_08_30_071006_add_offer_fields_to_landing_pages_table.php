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
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->decimal('regular_price', 10, 2)->nullable()->after('subtitle');
            $table->decimal('offer_price', 10, 2)->nullable()->after('regular_price');
            $table->dateTime('offer_end_date')->nullable()->after('offer_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn(['regular_price', 'offer_price', 'offer_end_date']);
        });
    }
};
