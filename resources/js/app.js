import './bootstrap';

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
        }
    });

    Alpine.store('app', {
        init() {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        }
    });
});
