<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class JenisKegiatan extends Model {
    protected $fillable = ['nama_jenis', 'keterangan'];
    public function kegiatans() {
        return $this->hasMany(Kegiatan::class, 'jenis_id');
    }
}