<?php

/*
===============================================================================
🔐 AUTENTIFIKASI - Register Controller (Web)
===============================================================================
Controller untuk registrasi user baru via web browser.

Setelah registrasi berhasil:
- User otomatis login (session-based)
- Redirect ke dashboard

Validasi:
- NIM: unique, required
- Password: required, min 8 characters, confirmed
- Name & Email: required
===============================================================================
*/

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nim' => ['required', 'string', 'max:50', Rule::unique('users', 'nim')],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Create user; password will be hashed via model cast
        $user = User::create([
            'nim' => $validated['nim'],
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'password' => $validated['password'],
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
