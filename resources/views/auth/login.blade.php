@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass auth-card" style="padding:24px 22px 22px; text-align:center;">
        {{-- Header sederhana --}}
        <div style="margin-bottom:18px;">
            <div
                style="
                    display:inline-block;
                    padding:6px 18px;
                    border-radius:999px;
                    font-size:13px;
                    font-weight:600;
                    background:rgba(139,92,246,0.15);
                    border:1px solid rgba(139,92,246,0.4);
                    color:#e5e7eb;
                "
            >
                ReminderApps
            </div>

            <h1
                class="dashboard-title"
                style="font-size:22px; margin-top:14px; margin-bottom:4px;"
            >
                Masuk ke akunmu
            </h1>
        </div>

        @if ($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:10px 12px; border-radius:12px; font-size:13px; margin-bottom:12px; text-align:left;">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form login --}}
        <form
            method="POST"
            action="{{ route('login.process') }}"
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
                <label for="password" style="font-size:13px; font-weight:600; display:block; margin-bottom:4px;">
                    Password
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="input"
                    required
                    placeholder="Password"
                >
            </div>

            <button type="submit" class="btn" style="margin-top:6px;">
                Masuk
            </button>
        </form>
    </div>
</div>
@endsection
