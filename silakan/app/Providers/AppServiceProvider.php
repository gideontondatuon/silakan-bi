<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
