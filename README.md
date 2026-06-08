# Task & Schedule Management System

Sistem Informasi Manajemen Tugas dan Penjadwalan Organisasi (Task & Schedule) berbasis web. Aplikasi ini dibangun dengan menggunakan framework **Laravel 11**, dirancang secara khusus untuk memfasilitasi delegasi pekerjaan, monitoring kinerja pegawai secara real-time, serta sentralisasi data kegiatan dalam suatu organisasi/instansi.

## 🌟 Fitur Utama
1. **Multi-Role Authentication**: 
   Akses antarmuka terspesialisasi untuk 3 level pengguna:
   - **Pimpinan**: Mendelegasikan To-Do list, memonitor progres kerja pegawai, dan melihat statistik kinerja.
   - **Admin**: Pengelolaan Master Data (Pegawai, Unit Kerja, Lokasi), sinkronisasi data kegiatan, dan dokumentasi arsitektur sistem.
   - **Pegawai**: Menerima tugas spesifik, mengerjakan laporan hasil pelaksanaan, dan melihat kalender kegiatan terkait dirinya.

2. **Smart Task Delegation (To-Do List)**:
   Pimpinan dapat memberikan instruksi spesifik kepada pegawai terpilih dengan sistem bobot (*weighting*), yang secara otomatis akan masuk ke _dashboard_ pegawai tersebut tanpa intervensi manual.

3. **Master Data Full CRUD (SPA-like Experience)**:
   Pengelolaan data unit, user, lokasi, dan jenis kegiatan telah menggunakan antarmuka interaktif berbasis **Alpine.js**. Formulir "Edit" menggunakan _modal overlay_ dinamis untuk pembaruan data yang cepat tanpa perlu _reload_ halaman (mencegah interupsi navigasi pengguna).

4. **Monitoring Kinerja**:
   Tabel _leaderboard_ kalkulasi kinerja berdasarkan penyelesaian bobot tugas dari masing-masing unit dan pegawai.

## 🛠️ Teknologi yang Digunakan
- **Backend**: Laravel 11.x, PHP 8.2+, MySQL
- **Frontend**: Blade Templating, Alpine.js (State Management UI), Vanilla CSS
- **Desain UI**: Kustomisasi penuh terinspirasi desain modern (_Glassmorphism & Micro-animations_)

## 🚀 Cara Instalasi & Menjalankan Aplikasi
1. Lakukan *clone* repositori:
   ```bash
   git clone https://github.com/donarazhar/todo.git
   ```
2. Instal pustaka PHP dan NPM:
   ```bash
   composer install
   npm install
   npm run build
   ```
3. Konfigurasi Environtment Database:
   - _Copy_ file `.env.example` ke `.env`
   - Buat _database_ bernama `todo` di MySQL lokal Anda.
   - Perbarui kredensial `.env`: `DB_DATABASE=todo`, `DB_USERNAME=root`, dsb.
4. Migrasi & Jalankan Aplikasi:
   ```bash
   php artisan key:generate
   php artisan migrate:fresh --seed
   php artisan serve
   ```
5. Akses melalui peramban web di `http://localhost:8000`.

---
*Aplikasi ini dirancang dengan sangat memprioritaskan User Experience dan estetik visual antarmuka pengguna.*
