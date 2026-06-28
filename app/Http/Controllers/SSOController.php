<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SSOController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('presensi')->redirect();
    }

    public function callback()
    {
        try {
            $presensiUser = Socialite::driver('presensi')->user();
            $email = $presensiUser->getEmail();
            $rawUser = $presensiUser->user;
            
            // Generate Photo URL
            $photoUrl = null;
            if (!empty($rawUser['foto'])) {
                $photoUrl = rtrim(env('PRESENSI_URL', 'https://presensigps.masjidagungalazhar.com'), '/') . '/storage/uploads/karyawan/' . $rawUser['foto'];
            }

            // Sync Unit
            $unitId = null;
            if (isset($rawUser['organ']['unit'])) {
                $unitData = $rawUser['organ']['unit'];
                \App\Models\UnitKerja::updateOrCreate(
                    ['id' => $unitData['id']],
                    ['nama_unit' => $unitData['name']]
                );
                $unitId = $unitData['id'];
            }

            // Determine Role (1=Admin, 2=Pimpinan, 3=Pegawai)
            $roleId = 3; // Default Pegawai
            if (isset($rawUser['organ']['name']) && stripos($rawUser['organ']['name'], 'kepala') !== false) {
                $roleId = 2; // Pimpinan
            }
            if ($email === 'donarazhar@gmail.com') {
                $roleId = 1; // Admin override
            }

            // Prevent overwriting existing privileged role if they are Admin (1)
            $existingUser = User::where('email', $email)->first();
            if ($existingUser && $existingUser->role_id == 1) {
                $roleId = 1;
            }

            // Update or Create User
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'nama' => $presensiUser->getName() ?? 'User',
                    'username' => explode('@', $email)[0],
                    'password' => bcrypt(\Illuminate\Support\Str::random(16)), // Auth is via SSO
                    'role_id' => $roleId,
                    'unit_id' => $unitId,
                    'foto' => $photoUrl,
                ]
            );

            if ($user) {
                Auth::login($user);
                return redirect()->intended('dashboard');
            }

            return redirect()->route('login')->withErrors([
                'username' => 'Gagal membuat atau menyinkronkan akun TODO Anda.'
            ]);

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'username' => 'Terjadi kesalahan saat mencoba login SSO. Silakan coba lagi. Error: ' . $e->getMessage()
            ]);
        }
    }
}
