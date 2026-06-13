<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable {
    use Notifiable, SoftDeletes;
    protected $fillable = ['nama', 'username', 'email', 'google_id', 'password', 'role_id', 'unit_id'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array {
        return ['password' => 'hashed'];
    }
    public function role() {
        return $this->belongsTo(Role::class);
    }
    public function unitKerja() {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }
    public function kegiatansCreated() {
        return $this->hasMany(Kegiatan::class, 'created_by');
    }
    public function kegiatans() {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_user');
    }
    public function tasksCreated() {
        return $this->hasMany(Task::class, 'created_by');
    }
    public function tasksAssigned() {
        return $this->hasMany(Task::class, 'assigned_to');
    }
}