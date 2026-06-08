<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan', 200);
            $table->foreignId('jenis_id')->constrained('jenis_kegiatans')->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('unit_kerjas')->onDelete('set null');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi_kegiatans')->onDelete('set null');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->enum('status', ['Belum', 'Berlangsung', 'Selesai'])->default('Belum');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kegiatans');
    }
};