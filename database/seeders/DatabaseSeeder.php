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
        // 1. Pastikan Role ada
        $roleAdmin = Role::firstOrCreate(['nama_role' => 'Admin']);
        $rolePimpinan = Role::firstOrCreate(['nama_role' => 'Pimpinan']);
        $rolePegawai = Role::firstOrCreate(['nama_role' => 'Pegawai']);

        // 2. Pastikan Admin ada (karena OrgSeeder akan menghapus selain admin)
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'password' => Hash::make('password'),
                'role_id' => $roleAdmin->id,
                'unit_id' => null,
            ]
        );

        // 3. Lokasi & Jenis Kegiatan (Opsional)
        LokasiKegiatan::firstOrCreate(['nama_lokasi' => 'Ruang Rapat Utama'], ['alamat' => 'Lantai 2']);
        LokasiKegiatan::firstOrCreate(['nama_lokasi' => 'Aula Gedung B'], ['alamat' => 'Lantai Dasar']);
        JenisKegiatan::firstOrCreate(['nama_jenis' => 'Rapat Koordinasi']);
        JenisKegiatan::firstOrCreate(['nama_jenis' => 'Pelatihan']);

        // 4. Seeder data dummy (OrgSeeder, TaskSeeder) telah dihapus karena tidak diperlukan di production.
    }
}
