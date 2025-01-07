<?php

use Filament\Pages\Dashboard;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Filament\Http\Responses\Auth\LoginResponse as BaseLoginResponse;

class LoginResponse extends BaseLoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        if (\Illuminate\Support\Facades\Auth::user()->is_admin) {
            return redirect()->to(Dashboard::getUrl(panel: 'admin'));
        }

        return parent::toResponse($request);
    }
}

// use Filament\Pages\Dashboard;
// use Illuminate\Http\RedirectResponse;
// use Livewire\Features\SupportRedirects\Redirector;
// use Filament\Http\Responses\Auth\LoginResponse as BaseLoginResponse;

// class LoginResponse extends BaseLoginResponse
// {
//     public function toResponse($request): RedirectResponse|Redirector
//     {
//         if (auth()->check() && auth()->user()->is_admin) {
//             return redirect()->to(Dashboard::getUrl(panel: 'admin'));
//         }

//         return parent::toResponse($request);
//     }
// }