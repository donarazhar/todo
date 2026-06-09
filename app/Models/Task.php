<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Task extends Model {
    use SoftDeletes;
    protected $fillable = ['judul', 'deskripsi', 'prioritas', 'bobot', 'tgl_mulai', 'tgl_selesai', 'status', 'laporan', 'file_laporan', 'sumber', 'created_by', 'assigned_to'];
    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];
    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function assignee() {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function getIsOverdueAttribute() {
        return $this->status !== 'Selesai' && $this->tgl_selesai < now()->startOfDay();
    }
    public function comments() {
        return $this->hasMany(TaskComment::class)->orderBy('created_at', 'asc');
    }
}