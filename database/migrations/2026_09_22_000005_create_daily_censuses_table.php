<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_censuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->date('census_date');
            $table->integer('initial_patients')->default(0); // Pasien sisa kemarin / awal bulan
            $table->integer('admissions_count')->default(0); // Pasien masuk hari ini
            $table->integer('transfers_in_count')->default(0); // Pindahan masuk
            $table->integer('transfers_out_count')->default(0); // Pindahan keluar
            $table->integer('discharges_count')->default(0); // Keluar hidup
            $table->integer('deaths_under_48h')->default(0); // Meninggal < 48 jam
            $table->integer('deaths_over_48h')->default(0); // Meninggal >= 48 jam
            $table->integer('remaining_patients')->default(0); // Sisa pasien (akhir hari)
            $table->integer('care_days')->default(0); // Hari Perawatan (HP)
            $table->integer('total_length_of_stay')->default(0); // Akumulasi LD pasien keluar
            $table->timestamps();

            $table->unique(['room_id', 'census_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_censuses');
    }
};
