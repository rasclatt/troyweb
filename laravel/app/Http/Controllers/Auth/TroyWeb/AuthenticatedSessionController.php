<?php

namespace App\Http\Controllers\Auth\TroyWeb;

use App\Http\Controllers\Auth\AuthenticatedSessionController as BaseController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Inertia\ {
    Inertia,
    Response
};

class AuthenticatedSessionController extends BaseController
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'heroImage' => app(\App\Services\SiteSetting::class)->getHeroImage(),
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
