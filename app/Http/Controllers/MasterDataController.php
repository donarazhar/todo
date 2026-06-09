<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\UnitKerja;
use App\Models\LokasiKegiatan;
use App\Models\JenisKegiatan;
use Illuminate\Support\Facades\Hash;

class MasterDataController extends Controller {
    public function index() {
        $users = User::with(['role', 'unitKerja'])->get();
        $roles = Role::all();
        $units = UnitKerja::with('kepalaUnit')->get();
        $lokasi = LokasiKegiatan::all();
        $jenis = JenisKegiatan::all();
        return view('admin.master-data', compact('users', 'roles', 'units', 'lokasi', 'jenis'));
    }

    // -- UNIT KERJA --
    public function storeUnit(Request $request) {
        $validated = $request->validate(['nama_unit' => 'required', 'kode_unit' => 'required']);
        UnitKerja::create($validated);
        return redirect()->back()->with('success', 'Unit Kerja berhasil ditambahkan.');
    }
    public function updateUnit(Request $request, $id) {
        $validated = $request->validate(['nama_unit' => 'required', 'kode_unit' => 'required']);
        UnitKerja::findOrFail($id)->update($validated);
        return redirect()->back()->with('success', 'Unit Kerja berhasil diubah.');
    }
    public function destroyUnit($id) { UnitKerja::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Unit dihapus.'); }

    // -- LOKASI --
    public function storeLokasi(Request $request) {
        $validated = $request->validate(['nama_lokasi' => 'required']);
        LokasiKegiatan::create($validated);
        return redirect()->back()->with('success', 'Lokasi berhasil ditambahkan.');
    }
    public function updateLokasi(Request $request, $id) {
        $validated = $request->validate(['nama_lokasi' => 'required']);
        LokasiKegiatan::findOrFail($id)->update($validated);
        return redirect()->back()->with('success', 'Lokasi berhasil diubah.');
    }
    public function destroyLokasi($id) { LokasiKegiatan::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Lokasi dihapus.'); }

    // -- JENIS --
    public function storeJenis(Request $request) {
        $validated = $request->validate(['nama_jenis' => 'required']);
        JenisKegiatan::create($validated);
        return redirect()->back()->with('success', 'Jenis Kegiatan berhasil ditambahkan.');
    }
    public function updateJenis(Request $request, $id) {
        $validated = $request->validate(['nama_jenis' => 'required']);
        JenisKegiatan::findOrFail($id)->update($validated);
        return redirect()->back()->with('success', 'Jenis Kegiatan berhasil diubah.');
    }
    public function destroyJenis($id) { JenisKegiatan::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Jenis Kegiatan dihapus.'); }

    // -- USERS --
    public function storeUser(Request $request) {
        $validated = $request->validate([
            'nama' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'unit_id' => 'required|exists:unit_kerjas,id'
        ]);
        $data = $validated;
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->back()->with('success', 'Pegawai / Pimpinan berhasil ditambahkan.');
    }
    public function updateUser(Request $request, $id) {
        $validated = $request->validate([
            'nama' => 'required|string',
            'username' => 'required|string|unique:users,username,'.$id,
            'role_id' => 'required|exists:roles,id',
            'unit_id' => 'required|exists:unit_kerjas,id',
            'password' => 'nullable|string|min:6'
        ]);
        $user = User::findOrFail($id);
        $data = collect($validated)->except('password')->toArray();
        if($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->back()->with('success', 'Data user berhasil diubah.');
    }
    public function destroyUser($id) { User::findOrFail($id)->delete(); return redirect()->back()->with('success', 'User dihapus.'); }
}