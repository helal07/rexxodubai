<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courier_charges', function (Blueprint $table) {
            $table->id();
            $table->string('district_name', 100);
            $table->decimal('charge', 10, 2)->default(120.00);
            $table->enum('zone_type', ['inside_dhaka', 'outside_dhaka', 'custom'])->default('outside_dhaka');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('district_name');
            $table->index('zone_type');
        });

        // Seed all 64 Bangladesh districts
        $insideDhaka = [
            'Dhaka', 'Gazipur', 'Narayanganj', 'Manikganj', 'Munshiganj',
            'Narsingdi', 'Tangail', 'Kishoreganj', 'Faridpur',
        ];

        $outsideDhaka = [
            'Chittagong', 'Sylhet', 'Rajshahi', 'Khulna', 'Barisal',
            'Rangpur', 'Mymensingh', 'Comilla', 'Noakhali', 'Feni',
            'Lakshmipur', 'Chandpur', 'Brahmanbaria', 'Cox\'s Bazar',
            'Rangamati', 'Khagrachhari', 'Bandarban', 'Jessore', 'Satkhira',
            'Bagerhat', 'Kushtia', 'Chuadanga', 'Meherpur', 'Narail',
            'Magura', 'Jhenaidah', 'Bogura', 'Chapai Nawabganj', 'Naogaon',
            'Joypurhat', 'Natore', 'Pabna', 'Sirajganj', 'Rajbari',
            'Gopalganj', 'Madaripur', 'Shariatpur', 'Habiganj', 'Moulvibazar',
            'Sunamganj', 'Dinajpur', 'Thakurgaon', 'Panchagarh', 'Nilphamari',
            'Lalmonirhat', 'Kurigram', 'Gaibandha', 'Jamalpur', 'Sherpur',
            'Netrokona', 'Patuakhali', 'Bhola', 'Barguna', 'Pirojpur',
            'Jhalokati',
        ];

        $now = now();

        foreach ($insideDhaka as $district) {
            DB::table('courier_charges')->insertOrIgnore([
                'district_name' => $district,
                'charge'        => 60.00,
                'zone_type'     => 'inside_dhaka',
                'is_active'     => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        foreach ($outsideDhaka as $district) {
            DB::table('courier_charges')->insertOrIgnore([
                'district_name' => $district,
                'charge'        => 120.00,
                'zone_type'     => 'outside_dhaka',
                'is_active'     => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('courier_charges');
    }
};
