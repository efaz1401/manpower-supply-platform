/*
| Desert Signal — ⌘K command palette.
| Registered as an Alpine.data component so the Blade markup stays declarative.
| The item list is built server-side (role-filtered) and passed in.
*/
export function registerPalette(Alpine) {
    Alpine.data('commandPalette', (items = []) => ({
        open: false,
        query: '',
        active: 0,
        items,

        get filtered() {
            const q = this.query.trim().toLowerCase();
            if (!q) return this.items;
            return this.items.filter((i) =>
                `${i.label} ${i.group} ${i.keywords}`.toLowerCase().includes(q)
            );
        },

        get groups() {
            return [...new Set(this.filtered.map((i) => i.group))];
        },

        inGroup(group) {
            return this.filtered.filter((i) => i.group === group);
        },

        indexOf(item) {
            return this.filtered.indexOf(item);
        },

        show() {
            this.open = true;
            this.query = '';
            this.active = 0;
            this.$nextTick(() => this.$refs.input?.focus());
        },

        hide() {
            this.open = false;
            this.query = '';
        },

        move(delta) {
            const n = this.filtered.length;
            if (!n) return;
            this.active = (this.active + delta + n) % n;
            this.$nextTick(() => {
                this.$refs.list
                    ?.querySelector('[data-active="true"]')
                    ?.scrollIntoView({ block: 'nearest' });
            });
        },

        run(item) {
            if (!item) return;
            if (item.type === 'theme') {
                this.$store.theme.toggle();
                this.hide();
                return;
            }
            window.location.href = item.url;
        },

        /** Global hotkeys: ⌘K / Ctrl+K toggles, "/" opens when not typing. */
        hotkey(event) {
            if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                this.open ? this.hide() : this.show();
                return;
            }
            if (event.key === '/' && !this.isTyping()) {
                event.preventDefault();
                this.show();
            }
        },

        isTyping() {
            const el = document.activeElement;
            if (!el) return false;
            return (
                ['INPUT', 'TEXTAREA', 'SELECT'].includes(el.tagName) ||
                el.isContentEditable
            );
        },
    }));
}
