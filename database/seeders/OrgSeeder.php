<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\UnitKerja;
use App\Models\User;

class OrgSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama terkait unit kerja dan user (kecuali admin)
        User::whereHas('role', function($q) { $q->where('nama_role', '!=', 'Admin'); })->delete();
        UnitKerja::query()->delete();

        $rolePimpinan = Role::where('nama_role', 'Pimpinan')->first();
        $rolePegawai = Role::where('nama_role', 'Pegawai')->first();

        // Helper function to create user and unit
        $createOrg = function($namaUser, $username, $namaUnit, $kodeUnit, $parentId = null) use ($rolePimpinan) {
            $user = User::create([
                'nama' => $namaUser,
                'username' => $username,
                'password' => Hash::make('password'),
                'role_id' => $rolePimpinan->id,
                'unit_id' => null // Set null first to avoid constraint
            ]);
            
            $unit = UnitKerja::create([
                'nama_unit' => $namaUnit,
                'kode_unit' => $kodeUnit,
                'kepala_unit_id' => $user->id,
                'parent_id' => $parentId
            ]);

            $user->update(['unit_id' => $unit->id]);
            return $unit;
        };

        // --- LEVEL 1: SEKRETARIAT ---
        $sekretariat = $createOrg('Drs. H. Yayat Suyatna, M.M', 'yayat', 'Sekretariat', 'SEK', null);

        // --- LEVEL 2: BAGIAN ---
        $ittd = $createOrg('Damarahmad Setiobudi, M.M', 'damarahmad', 'Bagian IT & Transformasi Digital', 'ITTD', $sekretariat->id);
        $kepeg = $createOrg('Ngadiman, M.Pd', 'ngadiman', 'Bagian Kepegawaian', 'KEPEG', $sekretariat->id);
        $tu = $createOrg('Hj. Min Amrina, S.Psi', 'amrina', 'Bagian Tata Usaha', 'TU', $sekretariat->id);
        $umum = $createOrg('H. Syamsul Arifin', 'syamsul', 'Bagian Umum', 'UMUM', $sekretariat->id);
        $humas = $createOrg('Subari, S.Pd', 'subari', 'Bagian Humas', 'HUMAS', $sekretariat->id);

        // --- LEVEL 3: SUB BAGIAN ---
        // ITTD
        $subTI = $createOrg('M. Noeseir, M.M.', 'noeseir', 'Sub Bagian Teknologi Informasi', 'STI', $ittd->id);
        $subTD = $createOrg('Bhayu Aditya P.', 'bhayu', 'Sub Bagian Transformasi Digital', 'STD', $ittd->id);

        // Kepegawaian
        $subAdmKep = $createOrg('Zainal Arifin, S.Pd.', 'zainal', 'Sub Bagian Administrasi Kepegawaian', 'SADMK', $kepeg->id);
        $subKesPeg = $createOrg('H. Alasri', 'alasri', 'Sub Bagian Kesejahteraan Pegawai', 'SKESP', $kepeg->id);
        $subPercPeg = $createOrg('Mukhtarom, S.Pd., M.M.', 'mukhtarom', 'Sub Bag. Perencanaan, Pembinaan & Peng. Karir Pegawai', 'SP3KP', $kepeg->id);

        // TU
        $subSurat = $createOrg('Ryan Ariska, SH', 'ryan', 'Sub Bagian Persuratan', 'SSUR', $tu->id);
        $subRumga = $createOrg('Bahrudin', 'bahrudin', 'Sub Bagian Rumah Tangga & Protokoler', 'SRTP', $tu->id);
        $subAman = $createOrg('Nasroni', 'nasroni', 'Sub Bagian Keamanan', 'SAMN', $tu->id);

        // Umum
        $subPengadaan = $createOrg('Pandu Wijaya, S.E.', 'pandu', 'Sub Bagian Pengadaan', 'SPENG', $umum->id);
        $subPelihara = $createOrg('Nursyamsi Atorida, S. Sos.', 'nursyamsi', 'Sub Bagian Pemeliharaan dan Inventaris', 'SPELI', $umum->id);

        // Humas
        $subKomPub = $createOrg('Eman Suherman, S.Psi.', 'eman', 'Sub Bagian Komunikasi & Publikasi', 'SKOMP', $humas->id);
        $subPasar = $createOrg('Teguh Budi Suswanto, S.E.', 'teguh', 'Sub Bagian Pemasaran', 'SPASR', $humas->id);

        // --- LEVEL 4: STAF (PEGAWAI) ---
        // Buat beberapa staf sebagai sampel
        $stafs = [
            ['nama' => 'Staf TI 1', 'username' => 'stafti1', 'unit_id' => $subTI->id],
            ['nama' => 'Staf TI 2', 'username' => 'stafti2', 'unit_id' => $subTI->id],
            ['nama' => 'Staf TD 1', 'username' => 'staftd1', 'unit_id' => $subTD->id],
            ['nama' => 'Staf Adm Kepeg 1', 'username' => 'stafadmk', 'unit_id' => $subAdmKep->id],
            ['nama' => 'Staf Humas (Publikasi)', 'username' => 'stafpub', 'unit_id' => $subKomPub->id],
        ];

        foreach ($stafs as $s) {
            User::create([
                'nama' => $s['nama'],
                'username' => $s['username'],
                'password' => Hash::make('password'),
                'role_id' => $rolePegawai->id,
                'unit_id' => $s['unit_id'],
            ]);
        }
    }
}
