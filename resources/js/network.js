/*
| Desert Signal — "The Network" hero scene.
| Dependency-free 2D canvas: worker nodes drift, edges connect nearby nodes,
| a compliance radar sweep highlights nodes it passes. Progressive enhancement:
|   - Only initialised when an element with [data-network] is present.
|   - Disabled entirely under prefers-reduced-motion (the static poster shows).
|   - Pauses when the tab is hidden or the canvas scrolls out of view.
*/
export function initNetwork() {
    const host = document.querySelector('[data-network]');
    if (!host) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const canvas = document.createElement('canvas');
    canvas.className = 'absolute inset-0 h-full w-full';
    host.appendChild(canvas);
    const ctx = canvas.getContext('2d');

    let W, H, dpr, nodes, raf, sweep = 0, running = true;

    const accent = () =>
        getComputedStyle(document.documentElement).getPropertyValue('--ds-accent').trim() || '5 150 105';
    const gold = () =>
        getComputedStyle(document.documentElement).getPropertyValue('--ds-gold').trim() || '233 184 114';

    function resize() {
        dpr = Math.min(window.devicePixelRatio || 1, 2);
        W = host.clientWidth;
        H = host.clientHeight;
        canvas.width = W * dpr;
        canvas.height = H * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        const count = Math.min(48, Math.max(18, Math.floor((W * H) / 22000)));
        nodes = Array.from({ length: count }, () => ({
            x: Math.random() * W,
            y: Math.random() * H,
            vx: (Math.random() - 0.5) * 0.25,
            vy: (Math.random() - 0.5) * 0.25,
            r: 1.5 + Math.random() * 2,
            lit: 0,
        }));
    }

    function frame() {
        if (!running) return;
        ctx.clearRect(0, 0, W, H);
        const a = accent();
        const cx = W / 2, cy = H / 2;

        // edges between nearby nodes
        for (let i = 0; i < nodes.length; i++) {
            for (let j = i + 1; j < nodes.length; j++) {
                const dx = nodes[i].x - nodes[j].x;
                const dy = nodes[i].y - nodes[j].y;
                const dist = Math.hypot(dx, dy);
                if (dist < 120) {
                    ctx.strokeStyle = `rgb(${a} / ${(1 - dist / 120) * 0.18})`;
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(nodes[i].x, nodes[i].y);
                    ctx.lineTo(nodes[j].x, nodes[j].y);
                    ctx.stroke();
                }
            }
        }

        // radar sweep highlights nodes near its angle
        sweep = (sweep + 0.012) % (Math.PI * 2);
        const g = gold();
        ctx.strokeStyle = `rgb(${g} / 0.25)`;
        ctx.beginPath();
        ctx.moveTo(cx, cy);
        ctx.lineTo(cx + Math.cos(sweep) * Math.max(W, H), cy + Math.sin(sweep) * Math.max(W, H));
        ctx.stroke();

        for (const n of nodes) {
            n.x += n.vx; n.y += n.vy;
            if (n.x < 0 || n.x > W) n.vx *= -1;
            if (n.y < 0 || n.y > H) n.vy *= -1;

            const ang = Math.atan2(n.y - cy, n.x - cx);
            const diff = Math.abs(((ang - sweep + Math.PI * 3) % (Math.PI * 2)) - Math.PI);
            if (diff < 0.12) n.lit = 1;
            n.lit *= 0.96;

            ctx.fillStyle = n.lit > 0.05
                ? `rgb(${g} / ${0.4 + n.lit * 0.6})`
                : `rgb(${a} / 0.7)`;
            ctx.beginPath();
            ctx.arc(n.x, n.y, n.r + n.lit * 2, 0, Math.PI * 2);
            ctx.fill();
        }

        raf = requestAnimationFrame(frame);
    }

    function start() { if (!running) { running = true; frame(); } }
    function stop() { running = false; cancelAnimationFrame(raf); }

    resize();
    frame();
    window.addEventListener('resize', resize);
    document.addEventListener('visibilitychange', () => (document.hidden ? stop() : start()));
    new IntersectionObserver((e) => (e[0].isIntersecting ? start() : stop()), { threshold: 0 }).observe(host);
}
