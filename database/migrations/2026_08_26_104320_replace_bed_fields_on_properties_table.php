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
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['bed_count', 'bed_type']);
            $table->unsignedInteger('king_size_bed_count')->default(0)->after('bathrooms');
            $table->unsignedInteger('single_bed_count')->default(0)->after('king_size_bed_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['king_size_bed_count', 'single_bed_count']);
            $table->unsignedInteger('bed_count')->default(0)->after('bathrooms');
            $table->string('bed_type')->nullable()->after('bed_count');
        });
    }
};
