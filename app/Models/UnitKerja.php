<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model {
    use HasFactory;
    protected $fillable = ['nama_unit', 'kode_unit', 'kepala_unit_id'];

    public function users() {
        return $this->hasMany(User::class, 'unit_id');
    }
    
    public function kepalaUnit() {
        return $this->belongsTo(User::class, 'kepala_unit_id');
    }
}