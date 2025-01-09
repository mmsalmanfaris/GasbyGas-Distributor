<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\LoginResponse;
use Filament\Http\Responses\Auth\LogoutResponse;

use Filament\Facades\Filament;

use App\Admin\Resources\UserResource;
use App\Admin\Resources\OutletManagementsResource;
use App\Admin\Resources\DispatchSchedulesResource;


class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        \Filament\Http\Responses\Auth\Contracts\LoginResponse::class => LoginResponse::class,
        \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class => LogoutResponse::class,
    ];
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
        schema::defaultStringLength(191);
    }
}
