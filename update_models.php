<?php
$models = [
    'Role.php' => <<<'EOD'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Role extends Model {
    protected $fillable = ['nama_role'];
    public function users() {
        return $this->hasMany(User::class);
    }
}
EOD,
    'UnitKerja.php' => <<<'EOD'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class UnitKerja extends Model {
    protected $fillable = ['nama_unit', 'kode_unit', 'kepala_unit_id'];
    public function users() {
        return $this->hasMany(User::class, 'unit_id');
    }
    public function kegiatans() {
        return $this->hasMany(Kegiatan::class, 'unit_id');
    }
}
EOD,
    'User.php' => <<<'EOD'
<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable {
    use Notifiable;
    protected $fillable = ['nama', 'username', 'password', 'role_id', 'unit_id'];
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
EOD,
    'LokasiKegiatan.php' => <<<'EOD'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LokasiKegiatan extends Model {
    protected $fillable = ['nama_lokasi', 'alamat'];
    public function kegiatans() {
        return $this->hasMany(Kegiatan::class, 'lokasi_id');
    }
}
EOD,
    'JenisKegiatan.php' => <<<'EOD'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class JenisKegiatan extends Model {
    protected $fillable = ['nama_jenis', 'keterangan'];
    public function kegiatans() {
        return $this->hasMany(Kegiatan::class, 'jenis_id');
    }
}
EOD,
    'Kegiatan.php' => <<<'EOD'
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
EOD,
    'Task.php' => <<<'EOD'
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
EOD
];

$dir = __DIR__ . '/app/Models/';
foreach ($models as $filename => $content) {
    file_put_contents($dir . $filename, $content);
    echo "Updated Model: $filename\n";
}
