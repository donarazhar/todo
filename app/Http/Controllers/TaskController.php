<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller {
    public function destroy($id) { $t=Task::findOrFail($id); if($t->created_by !== \Auth::id() && \Auth::user()->role->nama_role !== 'Admin') abort(403); $t->delete(); return redirect()->back()->with('success', 'Tugas dibatalkan/dihapus.'); }
    public function pimpinanTasks() {
        if (Auth::user()->role->nama_role !== 'Pimpinan') return abort(403);
        
        $tasks = Task::with(['assignee', 'creator'])
            ->where('created_by', Auth::id())
            ->orderBy('tgl_mulai', 'desc')->get();
            
        $pegawais = User::whereHas('role', function($q) {
            $q->where('nama_role', 'Pegawai');
        })->where('unit_id', Auth::user()->unit_id)->get();
        
        return view('dashboard.pimpinan', compact('tasks', 'pegawais'));
    }

    public function pegawaiTasks(Request $request) {
        if (Auth::user()->role->nama_role !== 'Pegawai') return abort(403);
        
        $tab = $request->query('tab', 'pimpinan');
        $sumber = $tab === 'mandiri' ? 'Mandiri' : 'Pimpinan';

        $tasks = Task::where('assigned_to', Auth::id())
                     ->where('sumber', $sumber)
                     ->orderBy('tgl_selesai', 'asc')
                     ->get();

        return view('dashboard.pegawai', compact('tasks', 'tab'));
    }

    public function store(Request $request) {
        $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
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
            'bobot' => 'required|integer|min:1|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $task->update($request->all());
        return redirect()->back()->with('success', 'Tugas berhasil diperbarui.');
    }

    public function submitReport(Request $request, $id) {
        $request->validate([
            'laporan' => 'required|string'
        ]);

        $task = Task::findOrFail($id);
        if ($task->assigned_to !== Auth::id()) {
            return abort(403, 'Unauthorized');
        }

        $task->laporan = $request->laporan;
        $task->status = 'Selesai';
        $task->save();

        return redirect()->back()->with('success', 'Laporan tugas berhasil dikirim.');
    }
}