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
    public function pimpinanTasks(Request $request) {
        $tab = $request->query('tab', 'delegasi');
        $myUnit = Auth::user()->unitKerja;
        $descendantIds = $myUnit ? $myUnit->getAllDescendantIds() : [];
        $allUnitIds = array_merge([Auth::user()->unit_id], $descendantIds);

        if (optional($myUnit)->parent_id === null) {
            // Pimpinan level tertinggi (Induk: -) hanya bisa menugaskan Pimpinan di child units langsung
            $childUnits = $myUnit->children()->pluck('id')->toArray();
            $pegawais = User::whereIn('unit_id', $childUnits)
                ->whereHas('role', function($q) {
                    $q->where('nama_role', 'Pimpinan');
                })
                ->where('id', '!=', Auth::id())
                ->get();
        } else {
            $pegawais = User::whereIn('unit_id', $allUnitIds)
                ->where('id', '!=', Auth::id())
                ->get();
        }
        $pegawaiIds = $pegawais->pluck('id')->toArray();

        $query = Task::with(['assignee', 'creator', 'comments.user']);

        if ($tab === 'delegasi') {
            $query->where('created_by', Auth::id())->where('sumber', 'Pimpinan');
        } elseif ($tab === 'masuk') {
            $query->where('assigned_to', Auth::id())->where('sumber', 'Pimpinan');
        } elseif ($tab === 'mandiri') {
            $query->where('assigned_to', Auth::id())->where('sumber', 'Mandiri');
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        $tasks = $query->orderBy('created_at', 'desc')
                       ->paginate(15)
                       ->appends($request->except('page'));
            
        return view('dashboard.pimpinan', compact('tasks', 'pegawais', 'tab'));
    }

    public function pimpinanMandiriTasks() {
        $myUnit = Auth::user()->unitKerja;
        $descendantIds = $myUnit ? $myUnit->getAllDescendantIds() : [];
        $allUnitIds = array_merge([Auth::user()->unit_id], $descendantIds);

        $pegawais = User::whereIn('unit_id', $allUnitIds)
            ->where('id', '!=', Auth::id())
            ->get();
        $pegawaiIds = $pegawais->pluck('id')->toArray();

        $query = Task::with(['assignee', 'comments.user'])
            ->where('sumber', 'Mandiri')
            ->whereIn('assigned_to', $pegawaiIds);

        if (request()->filled('search')) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . request()->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . request()->search . '%');
            });
        }
        
        if (request()->filled('status')) {
            $query->where('status', request()->status);
        }

        if (request()->filled('prioritas')) {
            $query->where('prioritas', request()->prioritas);
        }

        $mandiriTasks = $query->orderBy('created_at', 'desc')
                              ->paginate(15)
                              ->appends(request()->except('page'));
        
        return view('dashboard.pimpinan_mandiri', compact('mandiriTasks'));
    }

    public function pegawaiTasks(Request $request) {
        
        $tab = $request->query('tab', 'pimpinan');
        $sumber = $tab === 'mandiri' ? 'Mandiri' : 'Pimpinan';

        $query = Task::with(['comments.user'])
                     ->where('assigned_to', Auth::id())
                     ->where('sumber', $sumber);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        $tasks = $query->orderBy('created_at', 'desc')
                       ->paginate(15)
                       ->appends($request->except('page'));

        return view('dashboard.pegawai', compact('tasks', 'tab'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'prioritas' => 'required|in:Tinggi,Sedang,Rendah',
            'bobot' => 'required|integer|min:1|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $task = new Task($validated);
        $task->created_by = Auth::id();
        
        // Pimpinan maupun Pegawai bisa buat tugas mandiri dengan assign ke diri sendiri
        if ($task->assigned_to == Auth::id()) {
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
        
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'prioritas' => 'required|in:Tinggi,Sedang,Rendah',
            'bobot' => 'required|integer|min:1|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $task->update($validated);
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

    public function exportPdf(Request $request) {
        $tab = $request->query('tab', 'pimpinan');
        $sumber = $tab === 'mandiri' ? 'Mandiri' : 'Pimpinan';

        if (Auth::user()->role->nama_role === 'Pegawai') {
            $query = Task::with(['creator', 'assignee'])
                         ->where('assigned_to', Auth::id())
                         ->where('sumber', $sumber);
            $title = $sumber === 'Pimpinan' ? "Laporan Tugas Delegasi Pimpinan" : "Laporan Tugas Mandiri";
        } elseif (Auth::user()->role->nama_role === 'Pimpinan') {
            if ($tab === 'bawahan_mandiri') {
                $pegawais = User::whereHas('role', function($q) {
                    $q->where('nama_role', 'Pegawai');
                })->where('unit_id', Auth::user()->unit_id)->pluck('id')->toArray();
                
                $query = Task::with(['assignee'])
                             ->where('sumber', 'Mandiri')
                             ->whereIn('assigned_to', $pegawais);
                $title = "Laporan Tugas Mandiri Pegawai";
            } elseif ($tab === 'mandiri') {
                $query = Task::with(['assignee'])
                             ->where('assigned_to', Auth::id())
                             ->where('sumber', 'Mandiri');
                $title = "Laporan Tugas Mandiri Pimpinan";
            } else {
                $query = Task::with(['assignee'])
                             ->where('created_by', Auth::id())
                             ->where('sumber', 'Pimpinan');
                $title = "Laporan Tugas Delegasi Pimpinan";
            }
        } else {
            return abort(403);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.tasks_pdf', compact('tasks', 'title'));
        return $pdf->download('Laporan_Tugas_' . date('Y-m-d_Hi') . '.pdf');
    }
}