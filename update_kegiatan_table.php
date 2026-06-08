<?php
$adminBladePath = __DIR__ . '/resources/views/dashboard/admin.blade.php';
$adminBlade = file_get_contents($adminBladePath);

// 1. Update Table Headers
$headersOriginal = <<<'EOD'
                    <th>Lokasi</th>
                    <th>Waktu Mulai</th>
                    <th>Status</th>
                    <th>Aksi</th>
EOD;

$headersNew = <<<'EOD'
                    <th>Lokasi</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Peserta Terlibat</th>
                    <th>Status</th>
                    <th>Aksi</th>
EOD;

$adminBlade = str_replace($headersOriginal, $headersNew, $adminBlade);

// 2. Update Table Row Data
$rowOriginal = <<<'EOD'
                    <td>{{ $keg->lokasi->nama_lokasi ?? '-' }}</td>
                    <td>{{ $keg->waktu_mulai->format('d M Y, H:i') }}</td>
                    <td>
                        <span class="badge {{ $keg->status == 'Selesai' ? 'bg-selesai' : ($keg->status == 'Berlangsung' ? 'bg-proses' : 'bg-belum') }}">
                            {{ $keg->status }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('kegiatan.destroy', $keg->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
EOD;

$rowNew = <<<'EOD'
                    <td>{{ $keg->lokasi->nama_lokasi ?? '-' }}</td>
                    <td>{{ $keg->waktu_mulai->format('d M Y, H:i') }}</td>
                    <td style="color:var(--text-500);">{{ $keg->waktu_selesai->format('d M Y, H:i') }}</td>
                    <td>
                        <div style="max-width: 200px; white-space: normal; font-size: 11px;">
                            @if($keg->peserta->count() > 0)
                                {{ $keg->peserta->pluck('nama')->join(', ') }}
                            @else
                                <em style="color:var(--text-400);">Belum ada peserta</em>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $keg->status == 'Selesai' ? 'bg-selesai' : ($keg->status == 'Berlangsung' ? 'bg-proses' : 'bg-belum') }}">
                            {{ $keg->status }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('kegiatan.destroy', $keg->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
EOD;

$adminBlade = str_replace($rowOriginal, $rowNew, $adminBlade);
file_put_contents($adminBladePath, $adminBlade);
echo "Table updated successfully.\n";
