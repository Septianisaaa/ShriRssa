<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan field jenis pasien masuk
     */
    public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->enum('admission_type', ['new', 'transfer'])
                ->default('new')
                ->after('admission_date');
        });
    }

    /**
     * Menghapus field jika migration dibatalkan
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn('admission_type');
        });
    }
};