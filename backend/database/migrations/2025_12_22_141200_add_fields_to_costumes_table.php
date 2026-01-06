<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('costumes', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->string('brand')->nullable()->after('description');
            $table->string('featured_image_url')->nullable()->after('price_per_day');
            $table->boolean('is_active')->default(true)->after('featured_image_url');
        });
    }

    public function down(): void
    {
        Schema::table('costumes', function (Blueprint $table) {
            $table->dropColumn(['slug', 'brand', 'featured_image_url', 'is_active']);
        });
    }
};
