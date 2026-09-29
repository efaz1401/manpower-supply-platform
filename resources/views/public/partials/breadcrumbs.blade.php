@if (count($crumbs) > 1)
    <nav aria-label="Breadcrumb" class="mx-auto max-w-7xl px-4 pt-6 sm:px-6">
        <ol class="flex flex-wrap items-center gap-1.5 text-xs text-ink-faint">
            @foreach ($crumbs as $i => $crumb)
                <li class="flex items-center gap-1.5">
                    @if ($i > 0)
                        <span aria-hidden="true" class="rtl:rotate-180">&rsaquo;</span>
                    @endif
                    @if ($loop->last)
                        <span aria-current="page" class="text-ink-muted">{{ $crumb['name'] }}</span>
                    @else
                        <a href="{{ $crumb['url'] }}" class="transition-colors hover:text-accent">{{ $crumb['name'] }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
