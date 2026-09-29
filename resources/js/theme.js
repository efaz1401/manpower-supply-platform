/*
| Desert Signal — theme store (dark default + .light toggle).
| The pre-hydration <head> snippet in the layout sets the class before first
| paint to avoid FOUC; this module wires the reactive Alpine store + toggle.
*/
export function registerTheme(Alpine) {
    Alpine.store('theme', {
        current: document.documentElement.classList.contains('light') ? 'light' : 'dark',

        init() {
            this.apply(this.current);
        },

        toggle() {
            this.apply(this.current === 'dark' ? 'light' : 'dark');
        },

        apply(mode) {
            this.current = mode;
            document.documentElement.classList.toggle('light', mode === 'light');
            try {
                localStorage.setItem('ds-theme', mode);
            } catch (e) {
                /* storage disabled — session-only */
            }
        },
    });
}

/*
| Mobile sidebar drawer store. The aside is hidden below `md`; this store
| drives an off-canvas drawer + backdrop so the nav is reachable on phones.
*/
export function registerSidebar(Alpine) {
    Alpine.store('sidebar', {
        open: false,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        },
    });
}
