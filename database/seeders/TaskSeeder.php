<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data tugas lama
        Task::query()->delete();

        $users = User::with('unitKerja.parent')->whereHas('role', function($q) {
            $q->where('nama_role', '!=', 'Admin');
        })->get();

        $prioritas = ['Rendah', 'Sedang', 'Tinggi'];
        $status = ['Berlangsung', 'Menunggu Review', 'Revisi', 'Selesai'];

        foreach ($users as $user) {
            // 5 Tugas Mandiri
            for ($i = 1; $i <= 5; $i++) {
                $stat = $status[array_rand($status)];
                $tgl_selesai = Carbon::now()->addDays(rand(-5, 10)); // Bisa jadi sudah lewat (overdue)
                
                Task::create([
                    'judul' => 'Tugas Mandiri ' . $i . ' - ' . $user->nama,
                    'deskripsi' => 'Tugas inisiatif mandiri ke-' . $i . ' untuk melakukan evaluasi dan perencanaan harian.',
                    'prioritas' => $prioritas[array_rand($prioritas)],
                    'bobot' => rand(10, 30),
                    'tgl_mulai' => Carbon::now()->subDays(rand(1, 10))->format('Y-m-d'),
                    'tgl_selesai' => $tgl_selesai->format('Y-m-d'),
                    'status' => $stat,
                    'laporan' => ($stat == 'Selesai' || $stat == 'Menunggu Review') ? 'Laporan mandiri telah diselesaikan.' : null,
                    'sumber' => 'Mandiri',
                    'created_by' => $user->id,
                    'assigned_to' => $user->id,
                ]);
            }

            // Tentukan Atasan Langsung
            $atasanId = null;
            if ($user->unitKerja) {
                if ($user->id !== $user->unitKerja->kepala_unit_id && $user->unitKerja->kepala_unit_id) {
                    // Jika dia adalah staf, atasannya adalah kepala unitnya sendiri
                    $atasanId = $user->unitKerja->kepala_unit_id;
                } else if ($user->unitKerja->parent && $user->unitKerja->parent->kepala_unit_id) {
                    // Jika dia adalah kepala unit, atasannya adalah kepala dari unit induk (parent)
                    $atasanId = $user->unitKerja->parent->kepala_unit_id;
                }
            }

            // Jika dia punya atasan, buatkan 5 tugas delegasi
            if ($atasanId) {
                for ($i = 1; $i <= 5; $i++) {
                    $stat = $status[array_rand($status)];
                    $tgl_selesai = Carbon::now()->addDays(rand(-3, 14));
                    
                    Task::create([
                        'judul' => 'Delegasi ' . $i . ' untuk ' . explode(' ', trim($user->nama))[0],
                        'deskripsi' => 'Instruksi pimpinan ke-' . $i . '. Harap diselesaikan sesuai target waktu yang telah ditentukan.',
                        'prioritas' => $prioritas[array_rand($prioritas)],
                        'bobot' => rand(15, 40),
                        'tgl_mulai' => Carbon::now()->subDays(rand(1, 10))->format('Y-m-d'),
                        'tgl_selesai' => $tgl_selesai->format('Y-m-d'),
                        'status' => $stat,
                        'laporan' => ($stat == 'Selesai' || $stat == 'Menunggu Review') ? 'Laporan eksekusi tugas delegasi.' : null,
                        'sumber' => 'Pimpinan',
                        'created_by' => $atasanId,
                        'assigned_to' => $user->id,
                    ]);
                }
            }
        }
    }
}
