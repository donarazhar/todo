<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFour();
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            $notifPimpinan = 0;
            $notifPegawai = 0;

            if (\Illuminate\Support\Facades\Auth::check()) {
                $user = \Illuminate\Support\Facades\Auth::user();
                $role = $user->role->nama_role ?? '';

                if ($role === 'Pimpinan') {
                    $notifPimpinan = \App\Models\Task::where('created_by', $user->id)
                                         ->where('status', 'Menunggu Review')
                                         ->count();
                } elseif ($role === 'Pegawai') {
                    $pegawaiTasks = \App\Models\Task::where('assigned_to', $user->id)
                                        ->where('sumber', 'Pimpinan')
                                        ->get();

                    $notifPegawai = $pegawaiTasks->whereIn('status', ['Revisi'])->count();
                    $newTasks = $pegawaiTasks->where('status', 'Berlangsung')->whereNull('laporan')->count();
                    
                    $notifPegawai += $newTasks;
                }
            }

            $view->with(compact('notifPimpinan', 'notifPegawai'));
        });
    }
}
