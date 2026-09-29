import './bootstrap';
import { registerTheme, registerSidebar } from './theme';
import { registerMotion } from './motion';
import { registerPalette } from './palette';
import { initNetwork } from './network';

/*
| Alpine is bundled and started by Livewire 3. We register our stores and
| directives on `alpine:init`, which Livewire dispatches BEFORE Alpine.start(),
| so we never import or start a second Alpine instance.
*/
document.addEventListener('alpine:init', () => {
    // Signal that motion is live so x-reveal elements may hide-then-animate.
    // Until this fires, content stays visible (no blank page if Alpine fails).
    document.documentElement.classList.add('motion-ready');
    registerTheme(window.Alpine);
    registerSidebar(window.Alpine);
    registerPalette(window.Alpine);
    registerMotion(window.Alpine);
});

// The hero network scene self-guards: no-op unless [data-network] is present.
document.addEventListener('DOMContentLoaded', initNetwork);
document.addEventListener('livewire:navigated', initNetwork);
