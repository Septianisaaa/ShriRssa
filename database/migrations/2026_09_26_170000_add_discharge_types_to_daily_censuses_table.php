<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_censuses', function (Blueprint $table) {
            $table->integer('discharges_cured_count')->default(0)->after('discharges_count');
            $table->integer('discharges_aps_count')->default(0)->after('discharges_cured_count');
            $table->integer('discharges_run_count')->default(0)->after('discharges_aps_count');
            $table->integer('discharges_referred_count')->default(0)->after('discharges_run_count');
        });
    }

    public function down(): void
    {
        Schema::table('daily_censuses', function (Blueprint $table) {
            $table->dropColumn([
                'discharges_cured_count',
                'discharges_aps_count',
                'discharges_run_count',
                'discharges_referred_count',
            ]);
        });
    }
};
