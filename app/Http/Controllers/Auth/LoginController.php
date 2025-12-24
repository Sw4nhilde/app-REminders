<?php

/*
===============================================================================
🔐 AUTENTIFIKASI - Login Controller (Web)
===============================================================================
Controller untuk autentifikasi user via web browser (session-based).

Berbeda dengan API authentication:
- Web: Session & cookies (untuk browser)
- API: Bearer token (untuk mobile/external apps)

Endpoints:
- GET  /login  → Show login form
- POST /login  → Process login (session-based)
- POST /logout → Logout dan destroy session
===============================================================================
*/

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nim'      => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'nim'      => $request->nim,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'nim' => 'NIM atau password salah.',
        ])->onlyInput('nim');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
