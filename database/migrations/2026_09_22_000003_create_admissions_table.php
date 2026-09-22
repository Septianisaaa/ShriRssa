<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('current_room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('initial_room_id')->nullable()->constrained('rooms')->onDelete('set null');
            $table->dateTime('admission_date'); // Tanggal & jam masuk (bisa bulan/tahun lalu)
            $table->enum('status', ['active', 'discharged', 'transferred', 'deceased'])->default('active');
            $table->dateTime('discharge_date')->nullable();
            $table->enum('discharge_condition', ['cured', 'improved', 'unimproved', 'referred', 'aps', 'deceased_under_48h', 'deceased_over_48h'])->nullable();
            $table->integer('length_of_stay')->nullable(); // Lama Dirawat (LD) dalam hari
            $table->text('diagnosis')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
