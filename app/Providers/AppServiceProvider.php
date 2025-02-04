<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Pagination\LengthAwarePaginator;

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
        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=ON;');
        }
        if ($this->app->runningInConsole()) {
            Artisan::call('ai:serve'); // Start AI automatically
        }
        Collection::macro('paginate', function ($perPage = 15, $page = null) {
            $page = $page ?: request()->get('page', 1);
            $offset = ($page - 1) * $perPage;

            return (new LengthAwarePaginator(
                $this->slice($offset, $perPage)->values(),
                $this->count(),
                $perPage,
                $page,
                [
                    'path' => url()->current(),
                    'pageName' => 'page',
                ]
            ));
        });

    }
}
