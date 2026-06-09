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
            $table->softDeletes();
            $table->index('unit_id');
            $table->index('role_id');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('assigned_to');
            $table->index('created_by');
            $table->index('status');
            $table->index('sumber');
        });

        Schema::table('kegiatans', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('unit_id');
            $table->index('jenis_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['unit_id']);
            $table->dropIndex(['role_id']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['assigned_to']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['status']);
            $table->dropIndex(['sumber']);
        });

        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['unit_id']);
            $table->dropIndex(['jenis_id']);
        });
    }
};
