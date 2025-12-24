<!DOCTYPE html>
<html lang="id" x-data x-init="$store.darkMode.on && document.documentElement.classList.add('dark')">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReminderApps • {{ auth()->user()->name ?? '' }}</title>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- CSS Assets --}}
    <link rel="stylesheet" href="/css/app.css">

    {{-- PWA (boleh dinyalakan lagi nanti kalau sudah stabil) --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#8b5cf6">
    <link rel="apple-touch-icon" href="/icon-192.png">

    @livewireStyles

    <script defer src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="min-h-screen overflow-x-hidden">
    <div class="blob blob1"></div>
    <div class="blob blob2"></div>
    <div class="blob blob3"></div>

    <div class="max-w-3xl mx-auto p-6">
        @yield('content')
    </div>

    @livewireScripts

    {{-- JS Assets --}}
    <script defer src="/js/bootstrap.js"></script>
    <script defer src="/js/app.js"></script>

    <script>
        // Register service worker for PWA capabilities
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker
                    .register('/sw.js')
                    .then(reg => {
                        // Listen for updates and prompt refresh
                        if (reg.waiting) {
                            reg.waiting.postMessage({ type: 'SKIP_WAITING' });
                        }
                        reg.addEventListener('updatefound', () => {
                            const newWorker = reg.installing;
                            if (!newWorker) return;
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    // New content available; refresh to update assets
                                    console.log('New version available. Refresh to update.');
                                }
                            });
                        });
                    })
                    .catch(err => console.error('SW registration failed:', err));
            });
        }
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('darkMode', {
                on: false,
                init() {
                    const saved = localStorage.getItem('dark-mode');
                    this.on = saved === '1';
                    document.body.classList.toggle('dark', this.on);
                },
                toggle() {
                    this.on = !this.on;
                    document.body.classList.toggle('dark', this.on);
                    localStorage.setItem('dark-mode', this.on ? '1' : '0');
                },
            });

            Alpine.store('app', {
                init() {
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                },
            });
        });
    </script>
</body>
</html>
