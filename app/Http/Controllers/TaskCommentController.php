<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskComment;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller {
    public function store(Request $request, $taskId) {
        $task = Task::findOrFail($taskId);
        
        // Cek akses: hanya pembuat tugas, penerima tugas, atau admin yang bisa komen
        if ($task->created_by !== Auth::id() && $task->assigned_to !== Auth::id() && Auth::user()->role->nama_role !== 'Admin') {
            return abort(403, 'Unauthorized');
        }

        $request->validate([
            'komentar' => 'required|string'
        ]);

        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'komentar' => $request->komentar
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
