<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller {
    public function destroy($id) {
        $t = Task::findOrFail($id);
        if ($t->created_by !== \Illuminate\Support\Facades\Auth::id() && \Illuminate\Support\Facades\Auth::user()->role->nama_role !== 'Admin') {
            abort(403);
        }
        $t->delete();
        return redirect()->back()->with('success', 'Tugas dibatalkan/dihapus.');
    }
    public function pimpinanTasks() {
        
        $pegawais = User::whereHas('role', function($q) {
            $q->where('nama_role', 'Pegawai');
        })->where('unit_id', Auth::user()->unit_id)->get();
        $pegawaiIds = $pegawais->pluck('id')->toArray();

        $delegasiTasks = Task::with(['assignee', 'creator'])
            ->where('created_by', Auth::id())
            ->orderBy('tgl_mulai', 'desc')->paginate(15);
            
        return view('dashboard.pimpinan', compact('delegasiTasks', 'pegawais'));
    }

    public function pimpinanMandiriTasks() {
        
        $pegawais = User::whereHas('role', function($q) {
            $q->where('nama_role', 'Pegawai');
        })->where('unit_id', Auth::user()->unit_id)->get();
        $pegawaiIds = $pegawais->pluck('id')->toArray();

        $mandiriTasks = Task::with(['assignee'])
            ->where('sumber', 'Mandiri')
            ->whereIn('assigned_to', $pegawaiIds)
            ->orderBy('tgl_mulai', 'desc')->paginate(15);
        
        return view('dashboard.pimpinan_mandiri', compact('mandiriTasks'));
    }

    public function pegawaiTasks(Request $request) {
        
        $tab = $request->query('tab', 'pimpinan');
        $sumber = $tab === 'mandiri' ? 'Mandiri' : 'Pimpinan';

        $tasks = Task::where('assigned_to', Auth::id())
                     ->where('sumber', $sumber)
                     ->orderBy('tgl_selesai', 'asc')
                     ->paginate(15);

        return view('dashboard.pegawai', compact('tasks', 'tab'));
    }

    public function store(Request $request) {
        $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'prioritas' => 'required|in:Tinggi,Sedang,Rendah',
            'bobot' => 'required|integer|min:1|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $task = new Task($request->all());
        $task->created_by = Auth::id();
        
        if (Auth::user()->role->nama_role === 'Pegawai') {
            $task->assigned_to = Auth::id();
            $task->sumber = 'Mandiri';
        } else {
            $task->sumber = 'Pimpinan';
        }
        $task->status = 'Berlangsung';
        
        $task->save();
        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function update(Request $request, $id) {
        $task = Task::findOrFail($id);
        if ($task->created_by !== Auth::id() && $task->assigned_to !== Auth::id() && Auth::user()->role->nama_role !== 'Admin') {
            return abort(403);
        }
        
        $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'prioritas' => 'required|in:Tinggi,Sedang,Rendah',
            'bobot' => 'required|integer|min:1|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $task->update($request->all());
        return redirect()->back()->with('success', 'Tugas berhasil diperbarui.');
    }

    public function submitReport(Request $request, $id) {
        $task = Task::findOrFail($id);
        if ($task->assigned_to !== Auth::id()) {
            return abort(403, 'Unauthorized');
        }

        $request->validate([
            'laporan' => 'required|string',
            'file_laporan' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
        ]);

        $task->laporan = $request->laporan;
        
        if ($request->hasFile('file_laporan')) {
            $path = $request->file('file_laporan')->store('laporan_files', 'public');
            $task->file_laporan = $path;
        }

        // Tugas dari Pimpinan butuh direview, tugas Mandiri langsung Selesai
        $task->status = $task->sumber === 'Pimpinan' ? 'Menunggu Review' : 'Selesai';
        $task->save();

        return redirect()->back()->with('success', 'Laporan tugas berhasil dikirim.');
    }

    public function reviewTask(Request $request, $id) {
        $task = Task::findOrFail($id);
        
        // Hanya pembuat tugas (Pimpinan) atau Admin yang bisa mereview
        if ($task->created_by !== Auth::id() && Auth::user()->role->nama_role !== 'Admin') {
            return abort(403, 'Unauthorized');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($request->action === 'approve') {
            $task->status = 'Selesai';
            $msg = 'Laporan tugas telah disetujui (Selesai).';
        } else {
            $task->status = 'Revisi';
            $msg = 'Tugas dikembalikan ke Pegawai untuk revisi.';
        }
        
        $task->save();
        return redirect()->back()->with('success', $msg);
    }
}