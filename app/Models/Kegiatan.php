<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Kegiatan extends Model {
    protected $fillable = ['nama_kegiatan', 'jenis_id', 'unit_id', 'lokasi_id', 'waktu_mulai', 'waktu_selesai', 'status', 'created_by'];
    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];
    public function jenis() {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_id');
    }
    public function unitKerja() {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }
    public function lokasi() {
        return $this->belongsTo(LokasiKegiatan::class, 'lokasi_id');
    }
    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function peserta() {
        return $this->belongsToMany(User::class, 'kegiatan_user');
    }
}