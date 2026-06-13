<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Auth;

class KegiatanController extends Controller {
    public function destroy($id) { 
        Kegiatan::findOrFail($id)->delete(); 
        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus.'); 
    }
    public function index() {
        $kegiatans = Kegiatan::with(['jenis', 'lokasi', 'creator'])->orderBy('created_at', 'desc')->paginate(15);
        
        $jenis_kegiatans = \App\Models\JenisKegiatan::all();
        $unit_kerjas = \App\Models\UnitKerja::all();
        $lokasi_kegiatans = \App\Models\LokasiKegiatan::all();
        $allUsers = \App\Models\User::whereHas('role', function($q) { $q->whereIn('nama_role', ['Pegawai', 'Pimpinan']); })->with('role')->get()->map(function($u) {
            return [
                'id' => $u->id,
                'nama' => $u->nama,
                'role' => $u->role->nama_role,
                'unit_id' => $u->unit_id
            ];
        });

        return view('dashboard.admin', compact('kegiatans', 'jenis_kegiatans', 'unit_kerjas', 'lokasi_kegiatans', 'allUsers'));
    }

        public function store(Request $request) {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:200',
            'jenis_id' => 'required|exists:jenis_kegiatans,id',
            'unit_id' => 'required|exists:unit_kerjas,id',
            'lokasi_id' => 'required|exists:lokasi_kegiatans,id',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $data = $validated;
        $data['created_by'] = Auth::id();
        $kegiatan = Kegiatan::create($data);

        if ($request->has('user_ids')) {
            $kegiatan->peserta()->sync($request->user_ids);
        }

        return redirect()->back()->with('success', 'Jadwal kegiatan berhasil ditambahkan!');
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:200',
            'jenis_id' => 'required|exists:jenis_kegiatans,id',
            'unit_id' => 'required|exists:unit_kerjas,id',
            'lokasi_id' => 'required|exists:lokasi_kegiatans,id',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update(collect($validated)->except('user_ids')->toArray());

        if ($request->has('user_ids')) {
            $kegiatan->peserta()->sync($request->user_ids);
        } else {
            $kegiatan->peserta()->detach();
        }

        return redirect()->back()->with('success', 'Jadwal kegiatan berhasil diperbarui!');
    }
}