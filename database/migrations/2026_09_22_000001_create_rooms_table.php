<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('category'); // Lantai, Intensive/ICU, HCU, Paviliun, Perawatan Umum
            $table->string('floor')->nullable(); // Lantai 2, Lantai 3, Lantai 4, Lantai 6, Lantai 7, Lantai 8
            $table->integer('capacity')->default(10); // Kapasitas tempat tidur (TT)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
