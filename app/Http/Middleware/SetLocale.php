<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Resolve the active locale from (in order): the `{locale}` route segment
     * used by the public marketing site, the session, the authenticated user's
     * preference, then the app default. Arabic is the default.
     *
     * The route segment has to win so that /ar/... and /en/... are stable,
     * independently crawlable URLs — a session-driven locale would serve two
     * languages from one URL and only one of them could ever be indexed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys(config('app.supported_locales', ['ar' => [], 'en' => []]));

        $routeLocale = $request->route('locale');

        if (in_array($routeLocale, $supported, true)) {
            // Keep route() calls on marketing pages from having to pass the
            // locale explicitly.
            URL::defaults(['locale' => $routeLocale]);

            App::setLocale($routeLocale);
            session(['locale' => $routeLocale]);

            return $next($request);
        }

        $locale = session('locale');

        if (! $locale && $request->user()) {
            $locale = $request->user()->locale;
        }

        if (! in_array($locale, $supported, true)) {
            $locale = config('app.locale', 'ar');
        }

        URL::defaults(['locale' => $locale]);

        App::setLocale($locale);
        session(['locale' => $locale]);

        return $next($request);
    }
}
