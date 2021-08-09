<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   

        View::composer('*', function ($view) {
            $unread_noti_count = 0;
            $user = User::find(Auth::id());
            
            if($user){
                $unread_noti_count = $user->unreadNotifications()->count();
            }

            $view->with('unread_noti_count', $unread_noti_count);
        });
    }
}
