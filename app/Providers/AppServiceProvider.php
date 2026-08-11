<?php

namespace App\Providers;

use App\Models\AppNotification;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        //
        Paginator::useBootstrap();

        View::composer(['admin.common.navbar', 'page.common.navbar'], function ($view) {
            $adminNotifications = collect();
            $userNotifications = collect();
            $tourLocations = collect();
            $adminUnreadNotifications = 0;
            $userUnreadNotifications = 0;

            if (Schema::hasTable('locations') && Schema::hasTable('tours')) {
                $tourLocations = Location::active()
                    ->whereHas('tours')
                    ->orderBy('l_name')
                    ->get();
            }

            if (Schema::hasTable('app_notifications')) {
                if (Auth::guard('admins')->check()) {
                    $adminNotifications = AppNotification::forAdmin()
                        ->orderByDesc('id')
                        ->limit(6)
                        ->get();
                    $adminUnreadNotifications = AppNotification::forAdmin()->unread()->count();
                }

                if (Auth::guard('users')->check()) {
                    $userNotifications = AppNotification::forUser(Auth::guard('users')->id())
                        ->orderByDesc('id')
                        ->limit(6)
                        ->get();
                    $userUnreadNotifications = AppNotification::forUser(Auth::guard('users')->id())->unread()->count();
                }
            }

            $view->with(compact(
                'adminNotifications',
                'userNotifications',
                'tourLocations',
                'adminUnreadNotifications',
                'userUnreadNotifications'
            ));
        });
    }
}
