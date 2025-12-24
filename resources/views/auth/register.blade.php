@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass auth-card" style="padding:24px 22px 22px; text-align:center;">
        <div style="margin-bottom:18px;">
            <div
                style="
                    display:inline-block;
                    padding:6px 18px;
                    border-radius:999px;
                    font-size:13px;
                    font-weight:600;
                    background:rgba(16,185,129,0.15);
                    border:1px solid rgba(16,185,129,0.4);
                    color:#e5e7eb;
                "
            >
                ReminderApps
            </div>

            <h1
                class="dashboard-title"
                style="font-size:22px; margin-top:14px; margin-bottom:4px;"
            >
                Buat akun baru
            </h1>
        </div>

        @if ($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:10px 12px; border-radius:12px; font-size:13px; margin-bottom:12px; text-align:left;">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('register.process') }}"
            style="display:flex; flex-direction:column; gap:12px; margin-top:8px; text-align:left;"
        >
            @csrf

            <div>
                <label for="nim" style="font-size:13px; font-weight:600; display:block; margin-bottom:4px;">
                    NIM
                </label>
                <input
                    id="nim"
                    type="text"
                    name="nim"
                    class="input"
                    value="{{ old('nim') }}"
                    required
                    autofocus
                    placeholder="Masukkan NIM"
                >
            </div>

            <div>
                <label for="name" style="font-size:13px; font-weight:600; display:block; margin-bottom:4px;">
                    Nama Lengkap
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    class="input"
                    value="{{ old('name') }}"
                    required
                    placeholder="Masukkan nama"
                >
            </div>

            <div>
                <label for="email" style="font-size:13px; font-weight:600; display:block; margin-bottom:4px;">
                    Email (opsional)
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="input"
                    value="{{ old('email') }}"
                    placeholder="nama@kampus.ac.id"
                >
            </div>

            <div>
                <label for="password" style="font-size:13px; font-weight:600; display:block; margin-bottom:4px;">
                    Password
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="input"
                    required
                    placeholder="Minimal 6 karakter"
                >
            </div>

            <div>
                <label for="password_confirmation" style="font-size:13px; font-weight:600; display:block; margin-bottom:4px;">
                    Konfirmasi Password
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="input"
                    required
                    placeholder="Ulangi password"
                >
            </div>

            <button type="submit" class="btn" style="margin-top:6px;">
                Daftar
            </button>

            <div style="font-size:13px; margin-top:10px; text-align:center; opacity:.8;">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="link">Masuk di sini</a>
            </div>
        </form>
    </div>
</div>
@endsection
