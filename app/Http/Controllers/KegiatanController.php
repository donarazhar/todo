<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Auth;

class KegiatanController extends Controller {
    public function destroy($id) { Kegiatan::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Kegiatan berhasil dihapus.'); }
    public function index() {
        if (Auth::user()->role->nama_role !== 'Admin') return abort(403);
        $kegiatans = Kegiatan::with(['jenis', 'lokasi', 'creator'])->orderBy('waktu_mulai', 'asc')->get();
        return view('dashboard.admin', compact('kegiatans'));
    }

        public function store(Request $request) {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:200',
            'jenis_id' => 'required|exists:jenis_kegiatans,id',
            'unit_id' => 'required|exists:unit_kerjas,id',
            'lokasi_id' => 'required|exists:lokasi_kegiatans,id',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();
        $kegiatan = Kegiatan::create($data);

        if ($request->has('user_ids')) {
            $kegiatan->peserta()->sync($request->user_ids);
        }

        return redirect()->back()->with('success', 'Jadwal kegiatan berhasil ditambahkan!');
    }

    public function update(Request $request, $id) {
        $request->validate([
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
        $kegiatan->update($request->except('user_ids'));

        if ($request->has('user_ids')) {
            $kegiatan->peserta()->sync($request->user_ids);
        } else {
            $kegiatan->peserta()->detach();
        }

        return redirect()->back()->with('success', 'Jadwal kegiatan berhasil diperbarui!');
    }
}