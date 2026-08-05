<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
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
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Makassar');

        Paginator::useBootstrapFive();

        View::composer(
            'components.navbar',
            function ($view) {

                if(auth()->check()) {

                    $notifications =
                        auth()->user()
                        ->unreadNotifications()
                        ->latest()
                        ->take(5)
                        ->get();


                    $view->with(
                        'notifications',
                        $notifications
                    );

                }

            }
        );

    }

}
