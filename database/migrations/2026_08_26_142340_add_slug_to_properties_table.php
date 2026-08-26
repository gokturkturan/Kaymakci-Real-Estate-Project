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
            $table->string('slug')->nullable()->after('title');
        });

        foreach (\App\Models\Property::whereNull('slug')->orWhere('slug', '')->get() as $property) {
            $property->slug = \App\Models\Property::generateUniqueSlug($property->title, $property->location, $property->id);
            $property->saveQuietly();
        }

        Schema::table('properties', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
