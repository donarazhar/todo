<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Task extends Model {
    protected $fillable = ['judul', 'deskripsi', 'bobot', 'tgl_mulai', 'tgl_selesai', 'status', 'laporan', 'sumber', 'created_by', 'assigned_to'];
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
}