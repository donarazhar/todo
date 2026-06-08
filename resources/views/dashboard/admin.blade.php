@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Manajemen Penjadwalan Kegiatan</h2>
        <p>Buat dan kelola jadwal kegiatan organisasi secara terpusat</p>
    </div>
</div>

<div class="split-container" style="grid-template-columns: 28% 72%;" x-data="{ 
            editModalOpen: false, 
            editId: '', 
            editData: { user_ids: [] },
            openEditModal(id, data, users) {
                this.editId = id;
                this.editData = data;
                this.editData.user_ids = users;
                this.editModalOpen = true;
            }
        }">
    <div class="section-box">
        <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">➕</span> Buat Kegiatan Baru</h3>
        <form action="{{ route('kegiatan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" class="{{ $errors->has('nama_kegiatan') ? 'is-invalid' : '' }}" required>
                @error('nama_kegiatan') <small class="text-error">{{ $message }}</small> @enderror
            </div>
            <div class="form-group">
                <label>Jenis Kegiatan</label>
                <select name="jenis_id" required>
                    @foreach(\App\Models\JenisKegiatan::all() as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Unit Pelaksana</label>
                <select name="unit_id" required>
                    @foreach(\App\Models\UnitKerja::all() as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Lokasi</label>
                <select name="lokasi_id" required>
                    @foreach(\App\Models\LokasiKegiatan::all() as $lokasi)
                        <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Pegawai & Pimpinan Terlibat</label>
                <div class="checkbox-list">
                    @foreach(\App\Models\User::whereHas('role', function($q) { $q->whereIn('nama_role', ['Pegawai', 'Pimpinan']); })->get() as $u)
                        <label>
                            <input type="checkbox" name="user_ids[]" value="{{ $u->id }}">
                            {{ $u->nama }} ({{ $u->role->nama_role }})
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label>Waktu Mulai & Selesai</label>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <input type="datetime-local" name="waktu_mulai" required>
                    <input type="datetime-local" name="waktu_selesai" required>
                </div>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="Belum">Belum Berlangsung</option>
                    <option value="Berlangsung">Sedang Berlangsung</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn" style="width:100%;">📅 Publikasikan Jadwal</button>
        </form>
    </div>

    <div class="section-box">
        <h3 class="section-title"><span class="title-icon">📋</span> Database Jadwal Kegiatan</h3>
        <table>
            <thead>
                <tr>
                    <th>Detail Kegiatan</th>
                    <th>Waktu Pelaksanaan</th>
                    <th>Peserta Terlibat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatans as $keg)
                <tr>
                    <td>
                        <div style="font-weight:700; color:var(--text-900); font-size:13.5px; margin-bottom:4px;">{{ $keg->nama_kegiatan }}</div>
                        <div style="font-size:11.5px; color:var(--primary-500); font-weight:600; margin-bottom:2px;">🏢 {{ $keg->unitKerja->nama_unit ?? '-' }}</div>
                        <div style="font-size:11.5px; color:var(--text-500);">📍 {{ $keg->lokasi->nama_lokasi ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-size:12.5px; font-weight:600; color:var(--text-700);">Mulai: {{ $keg->waktu_mulai->format('d M Y, H:i') }}</div>
                        <div style="font-size:12px; color:var(--text-500); margin-top:3px;">Selesai: {{ $keg->waktu_selesai->format('d M Y, H:i') }}</div>
                    </td>
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
                    <td style="display:flex; gap:5px;">
                        <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $keg->id }}, { nama_kegiatan: '{{ addslashes($keg->nama_kegiatan) }}', jenis_id: '{{ $keg->jenis_id }}', unit_id: '{{ $keg->unit_id }}', lokasi_id: '{{ $keg->lokasi_id }}', waktu_mulai: '{{ $keg->waktu_mulai->format('Y-m-d\TH:i') }}', waktu_selesai: '{{ $keg->waktu_selesai->format('Y-m-d\TH:i') }}', status: '{{ $keg->status }}' }, {{ json_encode($keg->peserta->pluck('id')->toArray()) }})">✏️</button>
                        <form action="{{ route('kegiatan.destroy', $keg->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- ============================
         EDIT MODAL (ALPINE JS)
    ============================ -->
    <div class="modal-overlay" :class="{ 'show': editModalOpen }" x-show="editModalOpen" style="display: none;" x-transition>
        <div class="modal-box" @click.away="editModalOpen = false">
            <div class="modal-header">
                <h3>✏️ Edit Jadwal Kegiatan</h3>
                <button type="button" class="modal-close" @click="editModalOpen = false">×</button>
            </div>
            <form :action="'{{ url('/kegiatan') }}/' + editId" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" x-model="editData.nama_kegiatan" required>
                </div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                    <div class="form-group">
                        <label>Jenis Kegiatan</label>
                        <select name="jenis_id" x-model="editData.jenis_id" required>
                            @foreach(\App\Models\JenisKegiatan::all() as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Unit Pelaksana</label>
                        <select name="unit_id" x-model="editData.unit_id" required>
                            @foreach(\App\Models\UnitKerja::all() as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <select name="lokasi_id" x-model="editData.lokasi_id" required>
                        @foreach(\App\Models\LokasiKegiatan::all() as $lokasi)
                            <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Pegawai & Pimpinan Terlibat</label>
                    <div class="checkbox-list" style="max-height: 100px;">
                        @foreach(\App\Models\User::whereHas('role', function($q) { $q->whereIn('nama_role', ['Pegawai', 'Pimpinan']); })->get() as $u)
                            <label>
                                <input type="checkbox" name="user_ids[]" value="{{ $u->id }}" :checked="editData.user_ids && editData.user_ids.includes({{ $u->id }})">
                                {{ $u->nama }} ({{ $u->role->nama_role }})
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label>Waktu Mulai & Selesai</label>
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <input type="datetime-local" name="waktu_mulai" x-model="editData.waktu_mulai" required>
                        <input type="datetime-local" name="waktu_selesai" x-model="editData.waktu_selesai" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" x-model="editData.status" required>
                        <option value="Belum">Belum Berlangsung</option>
                        <option value="Berlangsung">Sedang Berlangsung</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" @click="editModalOpen = false">Batal</button>
                    <button type="submit" class="btn">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection