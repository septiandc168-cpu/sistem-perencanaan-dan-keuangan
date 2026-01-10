<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\RencanaKegiatan;
use App\Policies\RencanaKegiatanPolicy;

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
        // Implicitly grant "Super Admin" all permissions
        // This can be removed if you don't want super admins to have all permissions automatically
        Gate::before(function ($user, $ability) {
            if ($user->role->role_name === 'supervisor') {
                return true;
            }
        });

        // Register policies
        Gate::policy(RencanaKegiatan::class, RencanaKegiatanPolicy::class);
    }
}
