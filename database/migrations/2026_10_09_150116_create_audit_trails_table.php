<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();

            // Pengguna yang melakukan aktivitas
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('username')->nullable();
            $table->string('user_name')->nullable();

            // Informasi aktivitas
            $table->string('action')->nullable();
            $table->string('module')->nullable();
            $table->string('description')->nullable();

            // Detail perubahan data
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Informasi permintaan
            $table->string('method', 10)->nullable();
            $table->text('url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // Mempercepat pencarian berdasarkan waktu dan aktivitas
            $table->index('created_at');
            $table->index('action');
            $table->index('module');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};