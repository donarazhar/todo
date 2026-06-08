<?php
$controllers = [
    'AuthController.php' => <<<'EOD'
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function showLogin() {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
EOD,
    'DashboardController.php' => <<<'EOD'
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use App\Models\Task;
use App\Models\User;

class DashboardController extends Controller {
    public function index() {
        $user = Auth::user();
        $role = $user->role->nama_role;
        
        if ($role === 'Admin') {
            $kegiatans = Kegiatan::with(['jenis', 'lokasi', 'creator'])->orderBy('waktu_mulai', 'asc')->get();
            $tasks = Task::with(['assignee', 'creator'])->orderBy('tgl_mulai', 'asc')->get();
            $users = User::all();
            return view('dashboard.admin', compact('kegiatans', 'tasks', 'users'));
        } 
        elseif ($role === 'Pimpinan') {
            $kegiatans = Kegiatan::with(['jenis', 'lokasi', 'creator'])
                ->where('unit_id', $user->unit_id)
                ->orderBy('waktu_mulai', 'asc')->get();
            $tasks = Task::with(['assignee', 'creator'])
                ->where('created_by', $user->id)
                ->orWhereHas('assignee', function($q) use ($user) {
                    $q->where('unit_id', $user->unit_id);
                })
                ->orderBy('tgl_mulai', 'asc')->get();
            $pegawais = User::where('unit_id', $user->unit_id)->where('id', '!=', $user->id)->get();
            return view('dashboard.pimpinan', compact('kegiatans', 'tasks', 'pegawais'));
        } 
        elseif ($role === 'Pegawai') {
            $kegiatans = $user->kegiatans()->with(['jenis', 'lokasi', 'creator'])->orderBy('waktu_mulai', 'asc')->get();
            $tasks = Task::with(['creator'])->where('assigned_to', $user->id)->orderBy('tgl_mulai', 'asc')->get();
            return view('dashboard.pegawai', compact('kegiatans', 'tasks'));
        }

        return abort(403, 'Role not recognized');
    }
}
EOD,
    'TaskController.php' => <<<'EOD'
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'bobot' => 'required|integer|min:1|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'assigned_to' => 'nullable|exists:users,id' // Pegawai adds for themselves
        ]);

        $task = new Task($request->all());
        $task->created_by = Auth::id();
        
        if (Auth::user()->role->nama_role === 'Pegawai') {
            $task->assigned_to = Auth::id();
            $task->sumber = 'Mandiri';
        } else {
            $task->sumber = 'Pimpinan';
        }
        
        $task->save();
        return redirect()->back()->with('success', 'Task berhasil ditambahkan.');
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
