<?php

/*
| One-shot: map hardcoded Tailwind gray/white classes to Desert-Signal tokens
| across all Blade views, so dark mode themes correctly. Idempotent-ish: safe to
| run once. Review `git diff` afterwards. Skips the two hero layouts that are
| already hand-tokenized and the orphan nav (deleted separately).
|
| Run:  php scripts/tokenize-views.php
*/

$root = __DIR__.'/../resources/views';

// Order matters: longer/more-specific keys first.
$map = [
    // surfaces
    'bg-white' => 'bg-surface',
    'bg-gray-50' => 'bg-surface-sunken/60',
    'bg-gray-100' => 'bg-surface-sunken',
    'bg-gray-900' => 'bg-canvas',
    'bg-gray-800' => 'bg-surface-raised',

    // borders / dividers
    'border-gray-100' => 'border-hairline',
    'border-gray-200' => 'border-hairline',
    'border-gray-300' => 'border-hairline',
    'divide-gray-100' => 'divide-hairline',
    'divide-gray-200' => 'divide-hairline',

    // text
    'text-gray-900' => 'text-ink',
    'text-gray-800' => 'text-ink',
    'text-gray-700' => 'text-ink-muted',
    'text-gray-600' => 'text-ink-muted',
    'text-gray-500' => 'text-ink-faint',
    'text-gray-400' => 'text-ink-faint',

    // status chip backgrounds used inline (approximate to token tints)
    'bg-red-100' => 'bg-danger/15',
    'text-red-700' => 'text-danger',
    'text-red-600' => 'text-danger',
    'bg-amber-100' => 'bg-warning/15',
    'text-amber-700' => 'text-warning',
    'text-amber-600' => 'text-warning',
    'bg-emerald-100' => 'bg-success/15',
    'text-emerald-800' => 'text-success',
    'text-emerald-600' => 'text-accent',
    'bg-emerald-600' => 'bg-accent',
    'hover:bg-gray-50' => 'hover:bg-surface-raised',
    'hover:bg-gray-100' => 'hover:bg-surface-raised',
    'shadow-sm' => 'shadow-glass',

    // second pass: residual literals + form controls
    'hover:text-red-800' => 'hover:text-danger',
    'hover:text-blue-800' => 'hover:text-info',
    'text-blue-600' => 'text-info',
    'text-blue-800' => 'text-info',
    'bg-red-50' => 'bg-danger/10',
    'bg-emerald-50' => 'bg-accent/10',
    'bg-amber-50' => 'bg-warning/10',
    'border-emerald-200' => 'border-accent/25',
    'text-emerald-700' => 'text-success',
    'text-red-800' => 'text-danger',
    // native selects/textareas: replace the SHARED CORE fragment so every class-attr
    // shape (mt-1 / mt-1 block w-full / block w-full / bare, with/without disabled: suffix)
    // gets a dark surface + ink text. strtr picks the longest match at each position,
    // so this core wins wherever present; the short emerald fragments below catch the rest
    // (e.g. checkboxes that only have focus:ring-emerald-500).
    'border-hairline focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-glass' => 'bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass',
    'focus:border-emerald-500' => 'focus:border-accent',
    'focus:ring-emerald-500' => 'focus:ring-accent/40',
];

// Files to skip (already hand-tokenized or being removed).
$skip = [
    'welcome.blade.php',
    'layouts/app.blade.php',
    'layouts/guest.blade.php',
    'layouts/partials/sidebar.blade.php',
    'layouts/partials/topbar.blade.php',
    'components/application-logo.blade.php',
    'components/icon.blade.php',
    'components/stat-card.blade.php',
    'components/status-pill.blade.php',
    'components/modal.blade.php',
    'components/brand-button.blade.php',
    'components/primary-button.blade.php',
    'components/secondary-button.blade.php',
    'components/danger-button.blade.php',
    'components/text-input.blade.php',
    'components/input-label.blade.php',
    'components/input-error.blade.php',
    'components/nav-link.blade.php',
    'components/responsive-nav-link.blade.php',
    'dashboards/executive.blade.php',
    'dashboards/operations.blade.php',
    'livewire/layout/navigation.blade.php',
];

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$changed = 0;
foreach ($rii as $file) {
    if ($file->isDir() || ! str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }
    $rel = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
    if (in_array($rel, $skip, true)) {
        continue;
    }

    $src = file_get_contents($file->getPathname());
    $out = strtr($src, $map);

    if ($out !== $src) {
        file_put_contents($file->getPathname(), $out);
        echo "updated: $rel\n";
        $changed++;
    }
}

echo "\nDone. $changed file(s) updated.\n";
