// Dark mode store (Alpine.js-compatible)
window.darkMode = {
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
};

// Initialize dark mode on page load
document.addEventListener('DOMContentLoaded', () => {
    window.darkMode.init();
});

// Initialize icons if lucide is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        window.lucide.createIcons();
    }
});
