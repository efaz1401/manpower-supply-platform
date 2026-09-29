/*
| Desert Signal — motion layer.
| Registers Alpine directives used across the app. All directives are:
|   - RTL-aware (read <html dir>)
|   - reduced-motion safe (no-op / instant when the user opts out)
|   - GPU-cheap (transform/opacity only)
|
| Alpine is provided by Livewire 3 (do NOT import or start a second Alpine).
| We hook `alpine:init` which Livewire dispatches once its bundled Alpine boots.
*/

const prefersReduced = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const isRTL = () => document.documentElement.getAttribute('dir') === 'rtl';

export function registerMotion(Alpine) {
    /* x-reveal — fade/slide in. Reveals on next frame (layout settled), and also
       via observer for anything below the fold. Never leaves content hidden. */
    Alpine.directive('reveal', (el, { expression }, { evaluate }) => {
        if (prefersReduced()) {
            el.classList.add('is-revealed');
            return;
        }
        const delay = expression ? Number(evaluate(expression)) || 0 : 0;
        let done = false;
        const reveal = () => {
            if (done) return;
            done = true;
            el.style.animationDelay = `${delay}ms`;
            el.classList.add('is-revealed');
        };

        const io = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        reveal();
                        io.unobserve(el);
                    }
                });
            },
            { threshold: 0, rootMargin: '0px 0px -8% 0px' }
        );
        io.observe(el);

        // Safety net: if the observer hasn't fired shortly after layout settles
        // (e.g. element already on screen but observer missed it), reveal anyway.
        requestAnimationFrame(() =>
            requestAnimationFrame(() => {
                const r = el.getBoundingClientRect();
                const vh = window.innerHeight || document.documentElement.clientHeight;
                if (r.top < vh && r.bottom > 0) reveal();
            })
        );
        setTimeout(reveal, 400);
    });

    /*
    | x-parallax="0.15" — translate on scroll/mouse by a depth factor.
    | Mirrored horizontally in RTL. Disabled entirely under reduced-motion.
    */
    Alpine.directive('parallax', (el, { expression }, { evaluate }) => {
        if (prefersReduced()) return;
        const depth = expression ? Number(evaluate(expression)) || 0.1 : 0.1;
        const dir = isRTL() ? -1 : 1;

        const onScroll = () => {
            const rect = el.getBoundingClientRect();
            const offset = (rect.top + rect.height / 2 - window.innerHeight / 2) * depth;
            el.style.transform = `translate3d(0, ${(-offset).toFixed(2)}px, 0)`;
        };
        const onMove = (e) => {
            const cx = (e.clientX / window.innerWidth - 0.5) * 2;
            const cy = (e.clientY / window.innerHeight - 0.5) * 2;
            el.style.setProperty(
                '--px',
                `${(cx * depth * 30 * dir).toFixed(2)}px`
            );
            el.style.setProperty('--py', `${(cy * depth * 30).toFixed(2)}px`);
        };

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('mousemove', onMove, { passive: true });
        onScroll();

        el._dsCleanup = () => {
            window.removeEventListener('scroll', onScroll);
            window.removeEventListener('mousemove', onMove);
        };
    });

    /* x-countup="1234" — animate a number from 0 → value when revealed. */
    Alpine.directive('countup', (el, { expression }, { evaluate }) => {
        const target = Number(evaluate(expression)) || 0;
        if (prefersReduced()) {
            el.textContent = target.toLocaleString();
            return;
        }
        const run = () => {
            const duration = 1100;
            const start = performance.now();
            const step = (now) => {
                const t = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - t, 3);
                el.textContent = Math.round(target * eased).toLocaleString();
                if (t < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        };
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    run();
                    io.unobserve(el);
                }
            });
        });
        io.observe(el);
    });

    /* x-magnetic — button subtly follows the cursor. */
    Alpine.directive('magnetic', (el) => {
        if (prefersReduced()) return;
        const strength = 0.35;
        const onMove = (e) => {
            const r = el.getBoundingClientRect();
            const mx = e.clientX - (r.left + r.width / 2);
            const my = e.clientY - (r.top + r.height / 2);
            el.style.transform = `translate(${mx * strength}px, ${my * strength}px)`;
        };
        const reset = () => (el.style.transform = 'translate(0,0)');
        el.addEventListener('mousemove', onMove);
        el.addEventListener('mouseleave', reset);
    });
}
