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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'courier_name')) {
                $table->string('courier_name')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'courier_tracking_id')) {
                $table->string('courier_tracking_id')->nullable()->after('courier_name');
            }
            if (!Schema::hasColumn('orders', 'courier_consignment_id')) {
                $table->string('courier_consignment_id')->nullable()->after('courier_tracking_id');
            }
            if (!Schema::hasColumn('orders', 'courier_status')) {
                $table->string('courier_status')->nullable()->default('pending_dispatch')->after('courier_consignment_id');
            }
            if (!Schema::hasColumn('orders', 'courier_response')) {
                $table->text('courier_response')->nullable()->after('courier_status');
            }
            if (!Schema::hasColumn('orders', 'dispatched_at')) {
                $table->timestamp('dispatched_at')->nullable()->after('courier_response');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [
                'courier_name',
                'courier_tracking_id',
                'courier_consignment_id',
                'courier_status',
                'courier_response',
                'dispatched_at'
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
