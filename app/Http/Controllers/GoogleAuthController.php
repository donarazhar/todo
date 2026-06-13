<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists by email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update google_id if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId()
                    ]);
                }
                
                Auth::login($user);
                return redirect()->intended('dashboard');
            } else {
                // User doesn't exist, reject login
                return redirect()->route('login')->withErrors([
                    'username' => 'Email Google (' . $googleUser->getEmail() . ') tidak terdaftar di sistem kami. Hubungi Admin untuk mendaftarkan akun Anda.'
                ]);
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'username' => 'Terjadi kesalahan saat mencoba login dengan Google. Silakan coba lagi.'
            ]);
        }
    }
}
