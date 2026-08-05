<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect('/admin/dashboard');
        }

        return view('admin.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Check if user is an admin
            if (! Auth::user()->is_admin) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'You do not have administrative access.',
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();

            // Redirect to admin dashboard
            return redirect('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or unauthorized user.',
        ])->withInput($request->only('email'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin');
    }
}

