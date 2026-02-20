<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\RencanaKegiatan;
use App\Policies\RencanaKegiatanPolicy;
use App\Models\LaporanKegiatan;
use App\Policies\LaporanKegiatanPolicy;

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
        // Register policies
        Gate::policy(RencanaKegiatan::class, RencanaKegiatanPolicy::class);
        Gate::policy(LaporanKegiatan::class, LaporanKegiatanPolicy::class);
    }
}
