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
            $table->string('category'); // Instalasi (IPIT, IRNA 1, IRNA 2, IRNA 3, IRNA 4, IPJT, Paviliun, dll)
            $table->string('room_class'); // VIP, VVIP, Kelas 1, Kelas 2, Kelas 3
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
