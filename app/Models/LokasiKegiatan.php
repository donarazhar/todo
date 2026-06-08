<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LokasiKegiatan extends Model {
    protected $fillable = ['nama_lokasi', 'alamat'];
    public function kegiatans() {
        return $this->hasMany(Kegiatan::class, 'lokasi_id');
    }
}