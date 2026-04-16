<?php

namespace App\Providers;

use App\Models\PermissionRequest;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useTailwind();

        // get total status pending in permission
        view()->composer('*', function ($view) {
            $pendingCount = PermissionRequest::where('status', 'pending')->count();
            $view->with('permissionPendingCount', $pendingCount);
        });
    }
}
