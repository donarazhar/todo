<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\LokasiKegiatan;
use App\Models\JenisKegiatan;
use App\Models\Kegiatan;
use App\Models\Task;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // Roles
        $roleAdmin = Role::create(['nama_role' => 'Admin']);
        $rolePimpinan = Role::create(['nama_role' => 'Pimpinan']);
        $rolePegawai = Role::create(['nama_role' => 'Pegawai']);

        // Unit Kerja
        $unitIT = UnitKerja::create(['nama_unit' => 'Teknologi Informasi', 'kode_unit' => 'IT']);
        $unitHR = UnitKerja::create(['nama_unit' => 'SDM & Umum', 'kode_unit' => 'HR']);

        // Users
        $admin = User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role_id' => $roleAdmin->id,
            'unit_id' => null,
        ]);
        $pimpinan = User::create([
            'nama' => 'Bapak Pimpinan',
            'username' => 'pimpinan',
            'password' => Hash::make('password'),
            'role_id' => $rolePimpinan->id,
            'unit_id' => $unitIT->id,
        ]);
        $pegawai1 = User::create([
            'nama' => 'Budi Setiadi',
            'username' => 'budi',
            'password' => Hash::make('password'),
            'role_id' => $rolePegawai->id,
            'unit_id' => $unitIT->id,
        ]);
        $pegawai2 = User::create([
            'nama' => 'Siti Aminah',
            'username' => 'siti',
            'password' => Hash::make('password'),
            'role_id' => $rolePegawai->id,
            'unit_id' => $unitHR->id,
        ]);

        // Lokasi
        $ruangRapat = LokasiKegiatan::create(['nama_lokasi' => 'Ruang Rapat Utama', 'alamat' => 'Lantai 2']);
        $aula = LokasiKegiatan::create(['nama_lokasi' => 'Aula Gedung B', 'alamat' => 'Lantai Dasar']);

        // Jenis Kegiatan
        $jenisRapat = JenisKegiatan::create(['nama_jenis' => 'Rapat Koordinasi']);
        $jenisPelatihan = JenisKegiatan::create(['nama_jenis' => 'Pelatihan']);

        // Kegiatan
        $kegiatan1 = Kegiatan::create([
            'nama_kegiatan' => 'Evaluasi Kinerja Bulanan',
            'jenis_id' => $jenisRapat->id,
            'unit_id' => $unitIT->id,
            'lokasi_id' => $ruangRapat->id,
            'waktu_mulai' => now()->setTime(10, 0),
            'waktu_selesai' => now()->setTime(12, 0),
            'status' => 'Belum',
            'created_by' => $admin->id,
        ]);
        $kegiatan1->peserta()->attach([$pimpinan->id, $pegawai1->id]);

        $kegiatan2 = Kegiatan::create([
            'nama_kegiatan' => 'Briefing Tim',
            'jenis_id' => $jenisRapat->id,
            'unit_id' => $unitIT->id,
            'lokasi_id' => $ruangRapat->id,
            'waktu_mulai' => now()->setTime(13, 0),
            'waktu_selesai' => now()->setTime(14, 0),
            'status' => 'Berlangsung',
            'created_by' => $pimpinan->id,
        ]);
        $kegiatan2->peserta()->attach([$pegawai1->id, $pegawai2->id]);

        // Tasks
        Task::create([
            'judul' => 'Membuat Laporan Jaringan',
            'deskripsi' => 'Laporan rekapitulasi uptime server bulan ini.',
            'bobot' => 20,
            'tgl_mulai' => now()->format('Y-m-d'),
            'tgl_selesai' => now()->addDays(2)->format('Y-m-d'),
            'status' => 'Berlangsung',
            'sumber' => 'Pimpinan',
            'created_by' => $pimpinan->id,
            'assigned_to' => $pegawai1->id,
        ]);
        Task::create([
            'judul' => 'Update Antivirus Client',
            'deskripsi' => 'Pastikan semua PC menggunakan patch terbaru.',
            'bobot' => 15,
            'tgl_mulai' => now()->format('Y-m-d'),
            'tgl_selesai' => now()->addDays(1)->format('Y-m-d'),
            'status' => 'Selesai',
            'laporan' => 'Sudah terupdate 50 PC.',
            'sumber' => 'Mandiri',
            'created_by' => $pegawai1->id,
            'assigned_to' => $pegawai1->id,
        ]);
        Task::create([
            'judul' => 'Rekap Absensi',
            'deskripsi' => 'Tarik data dari mesin finger.',
            'bobot' => 30,
            'tgl_mulai' => now()->format('Y-m-d'),
            'tgl_selesai' => now()->addDays(3)->format('Y-m-d'),
            'status' => 'Berlangsung',
            'sumber' => 'Pimpinan',
            'created_by' => $pimpinan->id,
            'assigned_to' => $pegawai2->id,
        ]);
    }
}
