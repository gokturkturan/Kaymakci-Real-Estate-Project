<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('title_pl')->nullable()->after('title_en');
            $table->string('title_sk')->nullable()->after('title_pl');
            $table->string('title_ro')->nullable()->after('title_sk');

            $table->text('description_pl')->nullable()->after('description_en');
            $table->text('description_sk')->nullable()->after('description_pl');
            $table->text('description_ro')->nullable()->after('description_sk');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'title_pl', 'title_sk', 'title_ro',
                'description_pl', 'description_sk', 'description_ro',
            ]);
        });
    }
};
