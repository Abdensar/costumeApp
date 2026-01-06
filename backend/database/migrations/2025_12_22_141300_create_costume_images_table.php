<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('costume_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('costume_id')->constrained('costumes')->onDelete('cascade');
            $table->string('image_url');
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('costume_images');
    }
};
