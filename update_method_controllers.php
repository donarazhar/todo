<?php
$controllers = [
    'KegiatanController.php' => <<<'EOD'
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Auth;

class KegiatanController extends Controller {
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
            'status' => 'required|in:Belum,Berlangsung,Selesai'
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();
        Kegiatan::create($data);

        return redirect()->back()->with('success', 'Jadwal kegiatan berhasil ditambahkan!');
    }
}
EOD,
    'TaskController.php' => <<<'EOD'
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller {
    public function pimpinanTasks() {
        if (Auth::user()->role->nama_role !== 'Pimpinan') return abort(403);
        
        $tasks = Task::with(['assignee', 'creator'])
            ->where('created_by', Auth::id())
            ->orderBy('tgl_mulai', 'desc')->get();
            
        $pegawais = User::whereHas('role', function($q) {
            $q->where('nama_role', 'Pegawai');
        })->get();
        
        return view('dashboard.pimpinan', compact('tasks', 'pegawais'));
    }

    public function pegawaiTasks() {
        if (Auth::user()->role->nama_role !== 'Pegawai') return abort(403);
        
        $tasks = Task::where('assigned_to', Auth::id())->orderBy('tgl_selesai', 'asc')->get();
        return view('dashboard.pegawai', compact('tasks'));
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
EOD
];

$dir = __DIR__ . '/app/Http/Controllers/';
foreach ($controllers as $filename => $content) {
    file_put_contents($dir . $filename, $content);
    echo "Updated Controller: $filename\n";
}
