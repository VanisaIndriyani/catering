<?php

namespace App\Providers;

use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));
        Paginator::defaultView('pagination.default');

        View::composer('layouts.app', function ($view) {
            $view->with('jumlahKeranjang', (int) collect(session('cart', []))->sum('jumlah'));

            $notif = 0;
            $user = auth()->user();
            if ($user && $user->peran === 'pelanggan' && $user->pelanggan) {
                $notif = Pesanan::where('id_pelanggan', $user->pelanggan->id_pelanggan)
                    ->whereIn('status_pesanan', ['menunggu', 'diterima', 'diproses'])
                    ->count();
            }
            $view->with('jumlahNotifikasi', $notif);
        });
    }
}
