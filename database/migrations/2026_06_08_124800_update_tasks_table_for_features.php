<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah'])->default('Sedang')->after('deskripsi');
        });

        // Update the ENUM definition for status
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('Berlangsung', 'Menunggu Review', 'Revisi', 'Selesai') DEFAULT 'Berlangsung'");
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('prioritas');
        });

        // Revert the ENUM definition for status
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('Berlangsung', 'Selesai') DEFAULT 'Berlangsung'");
    }
};
