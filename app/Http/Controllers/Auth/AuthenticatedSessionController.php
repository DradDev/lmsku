<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Cek apakah role yang dipilih sesuai dengan role di database
        if ($request->role && $request->role !== $user->role) {

            Auth::logout();

            return back()->withErrors([
                'email' => 'The selected role does not match this account.',
            ]);
        }

        // Cek status registrasi user
        if ($user->registration_status === 'pending') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Your account registration is currently pending administrator approval.',
            ]);
        }

        if ($user->registration_status === 'rejected') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Your account registration has been rejected by the administrator.',
            ]);
        }

        // Redirect sesuai role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'lecturer') {
            return redirect()->route('lecturer.dashboard');
        }

        return redirect()->route('student.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}