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
            
            // Hardcode superadmin exception for donarazhar@gmail.com
            if ($email === 'donarazhar@gmail.com') {
                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'nama' => $presensiUser->getName() ?? 'Donar Azhar',
                        'username' => 'donarazhar',
                        'password' => bcrypt(\Illuminate\Support\Str::random(16)), // Dummy password since auth is via SSO
                        'role_id' => 1, // 1 is Admin role in DatabaseSeeder
                    ]
                );
            } else {
                // Check if user exists by email
                $user = User::where('email', $email)->first();
            }

            if ($user) {
                // If we need to sync anything during login, we can do it here.
                // KaryawanAdminController (Presensi) will handle the background auto-sync for data changes.
                
                Auth::login($user);
                return redirect()->intended('dashboard');
            } else {
                // User doesn't exist in TODO local DB, reject login
                return redirect()->route('login')->withErrors([
                    'username' => 'Email Anda (' . $email . ') belum didaftarkan di Aplikasi TODO. Hubungi Admin TODO untuk mendaftarkan akun Anda terlebih dahulu.'
                ]);
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'username' => 'Terjadi kesalahan saat mencoba login SSO. Silakan coba lagi. Error: ' . $e->getMessage()
            ]);
        }
    }
}
