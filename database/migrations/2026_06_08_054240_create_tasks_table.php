<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();
            $table->integer('bobot')->default(10);
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->enum('status', ['Berlangsung', 'Selesai'])->default('Berlangsung');
            $table->text('laporan')->nullable();
            $table->enum('sumber', ['Pimpinan', 'Mandiri'])->default('Mandiri');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('tasks');
    }
};