<?php
$dir = __DIR__ . '/database/migrations/';

$files = scandir($dir);
foreach ($files as $file) {
    if (str_ends_with($file, '.php')) {
        $content = '';
        if (str_contains($file, 'roles_table')) {
            $content = <<<'EOD'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_role', 50);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('roles');
    }
};
EOD;
        } elseif (str_contains($file, 'unit_kerjas_table')) {
            $content = <<<'EOD'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('unit_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_unit', 100);
            $table->string('kode_unit', 20)->nullable();
            $table->unsignedBigInteger('kepala_unit_id')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('unit_kerjas');
    }
};
EOD;
        } elseif (str_contains($file, 'users_table')) {
            $content = <<<'EOD'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('unit_kerjas')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }
    public function down(): void {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
EOD;
        } elseif (str_contains($file, 'lokasi_kegiatans_table')) {
            $content = <<<'EOD'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('lokasi_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi', 150);
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('lokasi_kegiatans');
    }
};
EOD;
        } elseif (str_contains($file, 'jenis_kegiatans_table')) {
            $content = <<<'EOD'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('jenis_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis', 100);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('jenis_kegiatans');
    }
};
EOD;
        } elseif (str_contains($file, 'kegiatans_table')) {
            $content = <<<'EOD'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan', 200);
            $table->foreignId('jenis_id')->constrained('jenis_kegiatans')->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('unit_kerjas')->onDelete('set null');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi_kegiatans')->onDelete('set null');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->enum('status', ['Belum', 'Berlangsung', 'Selesai'])->default('Belum');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kegiatans');
    }
};
EOD;
        } elseif (str_contains($file, 'kegiatan_user_table')) {
            $content = <<<'EOD'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('kegiatan_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kegiatan_user');
    }
};
EOD;
        } elseif (str_contains($file, 'tasks_table')) {
            $content = <<<'EOD'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();
            $table->integer('bobot')->default(10);
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->enum('status', ['Berlangsung', 'Selesai'])->default('Berlangsung');
            $table->text('laporan')->nullable();
            $table->enum('sumber', ['Pimpinan', 'Mandiri'])->default('Mandiri');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('tasks');
    }
};
EOD;
        }
        
        if ($content) {
            file_put_contents($dir . $file, $content);
            echo "Updated $file\n";
        }
    }
}
