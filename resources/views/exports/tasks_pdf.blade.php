<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .status-Selesai { color: #059669; font-weight: bold; }
        .status-Revisi { color: #DC2626; font-weight: bold; }
        .status-Berlangsung { color: #D97706; font-weight: bold; }
        .status-Menunggu { color: #2563EB; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $title }}</div>
        <div class="subtitle">Aplikasi Task&Schedule - Diekspor pada: {{ date('d M Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Judul Tugas</th>
                <th width="20%">Penanggung Jawab</th>
                <th width="20%">Periode</th>
                <th width="10%">Prioritas</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $index => $t)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $t->judul }}</strong><br>
                    <span style="font-size: 10px; color: #666;">{{ \Illuminate\Support\Str::limit($t->deskripsi, 50) }}</span>
                </td>
                <td>{{ $t->assignee->nama ?? '-' }}</td>
                <td>
                    M: {{ $t->tgl_mulai->format('d/m/Y') }}<br>
                    S: {{ $t->tgl_selesai->format('d/m/Y') }}
                </td>
                <td>{{ $t->prioritas }}</td>
                <td>
                    @php
                        $statusClass = '';
                        if($t->status === 'Selesai') $statusClass = 'status-Selesai';
                        elseif($t->status === 'Revisi') $statusClass = 'status-Revisi';
                        elseif($t->status === 'Berlangsung') $statusClass = 'status-Berlangsung';
                        else $statusClass = 'status-Menunggu';
                    @endphp
                    <span class="{{ $statusClass }}">{{ $t->status }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada tugas ditemukan pada filter ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="text-align: right; font-size: 10px; margin-top: 30px;">
        <em>Dokumen ini di-*generate* secara otomatis oleh Sistem Task&Schedule.</em>
    </div>
</body>
</html>
