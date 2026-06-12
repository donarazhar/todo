<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="refresh" content="300"> <!-- Auto refresh every 5 minutes -->
    <title>Monitoring & Presentasi - Task&Schedule</title>
    <link rel="icon" type="image/png" href="{{ asset('app-icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-50: #EFF6FF;
            --primary-100: #DBEAFE;
            --primary-400: #60A5FA;
            --primary-500: #3B82F6;
            --primary-600: #2563EB;
            --primary-700: #1D4ED8;
            --bg-app: #F8FAFC;
            --bg-card: #FFFFFF;
            --text-900: #0F172A;
            --text-700: #334155;
            --text-500: #64748B;
            --text-400: #94A3B8;
            --border-100: #F1F5F9;
            --border-200: #E2E8F0;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --shadow-sm: 0 1px 2px 0 rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --gradient-hero: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-app);
            color: var(--text-700);
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* HEADER */
        .header {
            background: var(--gradient-hero);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-md);
            z-index: 10;
        }
        .header-title { display: flex; align-items: center; gap: 16px; }
        .header-title img { width: 40px; height: 40px; }
        .header-title h1 { font-size: 24px; font-weight: 800; letter-spacing: -0.02em; }
        .header-title p { font-size: 14px; color: var(--primary-100); opacity: 0.9; }
        .header-clock { font-size: 20px; font-weight: 700; font-variant-numeric: tabular-nums; display: flex; align-items: center; gap: 8px; }

        /* MAIN CONTENT & TABS */
        .tabs-nav {
            display: flex;
            background: white;
            padding: 0 40px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            gap: 32px;
        }
        .tabs-nav button {
            background: none;
            border: none;
            padding: 16px 0;
            font-size: 16px;
            font-weight: 600;
            color: var(--text-500);
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }
        .tabs-nav button.active {
            color: var(--primary-600);
            border-bottom-color: var(--primary-600);
        }
        .tabs-nav button:hover:not(.active) {
            color: var(--text-900);
        }

        .main-content {
            flex: 1;
            padding: 24px 40px;
            overflow-y: auto;
        }

        .panel {
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-200);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .panel-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-100);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #F8FAFC;
        }
        .panel-header h2 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-900);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .panel-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 16px;
        }

        .panel-body {
            flex: 1;
            overflow-y: auto;
            padding: 0;
        }

        /* TABLES */
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th {
            background: #F1F5F9;
            color: var(--text-500);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 24px;
        }
        td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-100);
            font-size: 14px;
            color: var(--text-700);
        }
        tr { transition: background 0.2s ease; cursor: pointer; }
        tr:hover { background: var(--primary-50); }

        /* ACCORDION (DETAILS) */
        .pegawai-group {
            border-bottom: 1px solid var(--border-200);
        }
        .pegawai-group:last-child {
            border-bottom: none;
        }
        .pegawai-summary {
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 700;
            font-size: 16px;
            color: var(--text-900);
            list-style: none;
            transition: background 0.2s;
        }
        .pegawai-summary::-webkit-details-marker {
            display: none;
        }
        .pegawai-summary:hover {
            background: #F8FAFC;
        }
        .pegawai-summary::after {
            content: '\F282'; /* bootstrap bi-chevron-down */
            font-family: 'bootstrap-icons';
            margin-left: 10px;
            transition: transform 0.3s ease;
            color: var(--text-500);
        }
        .pegawai-group[open] .pegawai-summary::after {
            transform: rotate(180deg);
        }
        .pegawai-group[open] .pegawai-summary {
            background: #EFF6FF;
            border-bottom: 1px solid var(--border-200);
        }
        .pegawai-tasks {
            background: white;
        }

        /* KEGIATAN CARDS */
        .kegiatan-list {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            background: #F8FAFC;
        }
        .kegiatan-card {
            background: white;
            border-radius: var(--radius-md);
            padding: 20px;
            border: 1px solid var(--border-200);
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .kegiatan-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        .kegiatan-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .kegiatan-time {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-500);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .kegiatan-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-900);
            margin-bottom: 12px;
            line-height: 1.4;
        }
        .kegiatan-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        .meta-item {
            font-size: 14px;
            color: var(--text-700);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .kegiatan-peserta {
            background: #F1F5F9;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            color: var(--text-700);
            line-height: 1.6;
        }
        .kegiatan-peserta strong {
            color: var(--text-900);
            display: block;
            margin-bottom: 4px;
            font-size: 12px;
            text-transform: uppercase;
        }

        /* BADGES */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
        .bg-selesai { background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; }
        .bg-selesai::before { background: #059669; }
        .bg-proses { background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; }
        .bg-proses::before { background: #D97706; }
        .bg-belum { background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; }
        .bg-belum::before { background: #64748B; }
        
        /* MODAL */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 100;
            display: flex; align-items: center; justify-content: center;
            padding: 24px;
        }
        .modal-box {
            background: var(--bg-card);
            width: 100%; max-width: 600px;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }
        .modal-header {
            padding: 24px;
            border-bottom: 1px solid var(--border-100);
            display: flex; justify-content: space-between; align-items: flex-start;
        }
        .modal-header h3 { font-size: 20px; font-weight: 800; color: var(--text-900); line-height: 1.4; margin-bottom: 8px;}
        .modal-close {
            background: none; border: none; font-size: 24px; color: var(--text-500);
            cursor: pointer; padding: 4px; line-height: 1; transition: color 0.2s;
        }
        .modal-close:hover { color: var(--text-900); }
        .modal-body { padding: 24px; }
        .detail-row { margin-bottom: 16px; }
        .detail-label { font-size: 12px; color: var(--text-500); font-weight: 600; text-transform: uppercase; margin-bottom: 4px; }
        .detail-value { font-size: 15px; color: var(--text-900); font-weight: 500; }

        @media (max-width: 1024px) {
            .main-content { grid-template-columns: 1fr; overflow-y: auto; }
            .panel { min-height: 500px; }
            body { height: auto; overflow-y: auto; }
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
</head>
<body x-data="{
    currentTab: 'jadwal',
    modalOpen: false,
    modalType: '',
    modalData: {},
    openModal(type, data) {
        this.modalType = type;
        this.modalData = data;
        this.modalOpen = true;
    },
    currentTime: '',
    updateTime() {
        const now = new Date();
        this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
    }
}" x-init="updateTime(); setInterval(() => updateTime(), 1000);">

    <header class="header">
        <div class="header-title">
            <img src="{{ asset('app-icon.png') }}" alt="Logo" style="background: white; border-radius: 8px; padding: 4px;">
            <div>
                <h1>Dashboard Monitoring</h1>
                <p>Task&Schedule • Presentasi Live</p>
            </div>
        </div>
        <div class="header-clock">
            <i class="bi bi-clock"></i>
            <span x-text="currentTime"></span>
        </div>
    </header>

    <div class="tabs-nav">
        <button :class="{'active': currentTab === 'jadwal'}" @click="currentTab = 'jadwal'">
            <i class="bi bi-calendar-event" style="margin-right: 8px;"></i> Jadwal Kegiatan
        </button>
        <button :class="{'active': currentTab === 'progress'}" @click="currentTab = 'progress'">
            <i class="bi bi-person-workspace" style="margin-right: 8px;"></i> Progress Tugas
        </button>
    </div>

    <main class="main-content">
        <!-- PANEL: JADWAL KEGIATAN -->
        <section class="panel" x-show="currentTab === 'jadwal'" style="display: none;">
            <div class="panel-header">
                <h2>
                    <div class="panel-icon" style="background: #10B981;"><i class="bi bi-calendar-event"></i></div>
                    Jadwal Kegiatan Bulan Ini
                </h2>
                <div class="badge" style="background: #D1FAE5; color: #047857;">Total: {{ $kegiatans->count() }} Kegiatan</div>
            </div>
            <div class="panel-body">
                <div class="kegiatan-list">
                    @forelse($kegiatans as $k)
                    <div class="kegiatan-card">
                        <div class="kegiatan-card-header">
                            <div class="kegiatan-time">
                                <i class="bi bi-calendar-check"></i> {{ $k->waktu_mulai->format('d M Y') }} • <i class="bi bi-clock"></i> {{ $k->waktu_mulai->format('H:i') }} - {{ $k->waktu_selesai->format('H:i') }} WIB
                            </div>
                            <span class="badge {{ $k->status == 'Selesai' ? 'bg-selesai' : ($k->status == 'Berlangsung' ? 'bg-proses' : 'bg-belum') }}">{{ $k->status }}</span>
                        </div>
                        <h3 class="kegiatan-title">{{ $k->nama_kegiatan }}</h3>
                        <div class="kegiatan-meta">
                            <div class="meta-item"><i class="bi bi-geo-alt-fill" style="color: #EF4444;"></i> {{ $k->lokasi->nama_lokasi ?? '-' }}</div>
                            <div class="meta-item"><i class="bi bi-building" style="color: #3B82F6;"></i> {{ $k->unitKerja->nama_unit ?? '-' }}</div>
                        </div>
                        <div class="kegiatan-peserta">
                            <strong><i class="bi bi-people-fill"></i> Peserta Terlibat:</strong>
                            {{ $k->peserta->count() > 0 ? $k->peserta->pluck('nama')->join(', ') : 'Belum ada peserta yang ditugaskan.' }}
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 40px; color: var(--text-500);">Tidak ada kegiatan terjadwal bulan ini.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- PANEL: TUGAS PEGAWAI -->
        <section class="panel" x-show="currentTab === 'progress'" style="display: none;">
            <div class="panel-header">
                <h2>
                    <div class="panel-icon" style="background: #3B82F6;"><i class="bi bi-person-workspace"></i></div>
                    Progress Tugas per Pegawai
                </h2>
                <div class="badge" style="background: #E0E7FF; color: #4338CA;">Total: {{ $tasks->count() }} Tugas</div>
            </div>
            <div class="panel-body">
                @forelse($tasksGrouped as $pegawai => $tasksByPegawai)
                <details class="pegawai-group">
                    <summary class="pegawai-summary">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary-100); color: var(--primary-700); display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                {{ substr($pegawai, 0, 1) }}
                            </div>
                            <div>
                                <div>{{ $pegawai }}</div>
                                <div style="font-size: 12px; color: var(--text-500); font-weight: 500;">{{ $tasksByPegawai->first()->assignee->unitKerja->nama_unit ?? 'Tugas Mandiri/Pimpinan' }}</div>
                            </div>
                        </div>
                        <div>
                            <span class="badge bg-belum" style="margin-right: 16px;">{{ count($tasksByPegawai) }} Tugas</span>
                        </div>
                    </summary>
                    <div class="pegawai-tasks">
                        <table>
                            <thead>
                                <tr>
                                    <th>Judul Tugas</th>
                                    <th>Target Selesai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasksByPegawai as $t)
                                <tr @click="openModal('task', {{ \Illuminate\Support\Js::from([
                                    'judul' => $t->judul,
                                    'deskripsi' => $t->deskripsi,
                                    'pegawai' => $t->assignee->nama ?? '-',
                                    'unit' => $t->assignee->unitKerja->nama_unit ?? '-',
                                    'prioritas' => $t->prioritas,
                                    'tgl_mulai' => $t->tgl_mulai->format('d M Y'),
                                    'tgl_selesai' => $t->tgl_selesai->format('d M Y'),
                                    'status' => $t->status,
                                    'bobot' => $t->bobot
                                ]) }})">
                                    <td style="width: 50%;">
                                        <div style="font-weight: 700; color: var(--text-900); margin-bottom: 4px;">{{ $t->judul }}</div>
                                        <div style="font-size: 13px; color: var(--text-500);">Bobot: {{ $t->bobot }} • Prioritas: {{ $t->prioritas }}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $t->tgl_selesai->format('d M Y') }}</div>
                                        @if($t->is_overdue)
                                            <div style="font-size: 12px; color: #DC2626; font-weight: 700; margin-top: 4px;"><i class="bi bi-exclamation-triangle-fill"></i> Terlambat</div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusBg = 'bg-proses';
                                            if($t->status === 'Selesai') $statusBg = 'bg-selesai';
                                            elseif($t->status === 'Menunggu Review') $statusBg = 'bg-proses';
                                            elseif($t->status === 'Revisi') $statusBg = 'bg-belum';
                                        @endphp
                                        <span class="badge {{ $statusBg }}">{{ $t->status }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
                @empty
                <div style="text-align: center; padding: 40px; color: var(--text-500);">Tidak ada tugas aktif.</div>
                @endforelse
            </div>
        </section>
    </main>

    <!-- MODAL -->
    <div class="modal-overlay" style="display: none;" x-show="modalOpen" x-transition>
        <div class="modal-box" @click.away="modalOpen = false">
            
            <!-- Modal Template for TASK -->
            <template x-if="modalType === 'task'">
                <div>
                    <div class="modal-header">
                        <div>
                            <span class="badge bg-proses" style="margin-bottom: 12px; display: inline-flex;" x-text="modalData.status"></span>
                            <h3 x-text="modalData.judul"></h3>
                            <div style="font-size: 14px; color: var(--primary-600); font-weight: 600;"><i class="bi bi-person-fill"></i> <span x-text="modalData.pegawai"></span> (<span x-text="modalData.unit"></span>)</div>
                        </div>
                        <button class="modal-close" @click="modalOpen = false"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="detail-row">
                            <div class="detail-label">Deskripsi Tugas</div>
                            <div class="detail-value" style="font-weight: 400; line-height: 1.6;" x-text="modalData.deskripsi || 'Tidak ada deskripsi.'"></div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="detail-row">
                                <div class="detail-label">Tanggal Pelaksanaan</div>
                                <div class="detail-value"><span x-text="modalData.tgl_mulai"></span> - <span x-text="modalData.tgl_selesai"></span></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Prioritas & Bobot</div>
                                <div class="detail-value">
                                    <span style="font-weight: 700; color: #DC2626;" x-show="modalData.prioritas === 'Tinggi'">Tinggi</span>
                                    <span style="font-weight: 700; color: #D97706;" x-show="modalData.prioritas === 'Sedang'">Sedang</span>
                                    <span style="font-weight: 700; color: #059669;" x-show="modalData.prioritas === 'Rendah'">Rendah</span>
                                    • Bobot <span x-text="modalData.bobot"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Modal Template for KEGIATAN -->
            <template x-if="modalType === 'kegiatan'">
                <div>
                    <div class="modal-header">
                        <div>
                            <span class="badge bg-belum" style="margin-bottom: 12px; display: inline-flex;" x-text="modalData.status"></span>
                            <h3 x-text="modalData.nama"></h3>
                            <div style="font-size: 14px; color: var(--text-500); font-weight: 600;"><i class="bi bi-building"></i> <span x-text="modalData.unit"></span></div>
                        </div>
                        <button class="modal-close" @click="modalOpen = false"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="detail-row">
                            <div class="detail-label">Lokasi Kegiatan</div>
                            <div class="detail-value"><i class="bi bi-geo-alt-fill" style="color: #EF4444;"></i> <span x-text="modalData.lokasi"></span></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Waktu Pelaksanaan</div>
                            <div class="detail-value"><i class="bi bi-clock-history"></i> <span x-text="modalData.waktu_mulai"></span> s/d <span x-text="modalData.waktu_selesai"></span></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Peserta Terlibat</div>
                            <div class="detail-value" style="font-weight: 400; line-height: 1.6; max-height: 100px; overflow-y: auto;" x-text="modalData.peserta"></div>
                        </div>
                    </div>
                </div>
            </template>

        </div>
    </div>
</body>
</html>
