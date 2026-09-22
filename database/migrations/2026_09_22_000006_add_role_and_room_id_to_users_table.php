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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->enum('role', ['superadmin', 'admin'])->default('admin')->after('email');
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete()->after('role');
            $table->string('phone')->nullable()->after('room_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn(['username', 'role', 'room_id', 'phone']);
        });
    }
};
