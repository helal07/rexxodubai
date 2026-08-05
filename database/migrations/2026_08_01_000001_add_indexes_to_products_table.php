<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for database query performance optimization.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['gender', 'is_featured', 'is_new_arrival']);
            $table->index(['scent_family']);
            $table->index(['concentration']);
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->index(['parent_id', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['gender', 'is_featured', 'is_new_arrival']);
            $table->dropIndex(['scent_family']);
            $table->dropIndex(['concentration']);
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropIndex(['parent_id', 'is_active', 'sort_order']);
        });
    }
};
