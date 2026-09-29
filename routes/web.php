<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Portal\ClientPortalController;
use App\Http\Controllers\Portal\WorkerPortalController;
use App\Http\Controllers\PublicSiteController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Public marketing site
|--------------------------------------------------------------------------
|
| Every public URL is locale-prefixed (/ar/..., /en/...) so each language is a
| distinct, crawlable, canonical URL that can carry its own hreflang. The
| authenticated app below stays unprefixed and is excluded from indexing.
|
*/

// `/` is not a content URL — it hands off to the default locale. A 302 (not
// 301) is what Google recommends for locale landing redirects.
Route::get('/', function () {
    $locale = in_array(session('locale'), array_keys(config('app.supported_locales')), true)
        ? session('locale')
        : config('app.locale', 'ar');

    return redirect("/$locale", 302);
})->name('root');

Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('llms.txt', [SitemapController::class, 'llms'])->name('llms');

Route::prefix('{locale}')
    ->whereIn('locale', array_keys(config('app.supported_locales')))
    ->name('public.')
    ->controller(PublicSiteController::class)
    ->group(function () {
        Route::get('/', 'home')->name('home');
        Route::get('services', 'services')->name('services');
        Route::get('services/{service}', 'service')->name('service');
        Route::get('manpower-supply/{city}', 'city')->name('city');
        Route::get('ajeer', 'ajeer')->name('ajeer');
        Route::get('about', 'about')->name('about');
        Route::get('faq', 'faq')->name('faq');
        Route::get('contact', 'contact')->name('contact');
        Route::get('thank-you', 'thankYou')->name('thank-you');
    });

// Locale switcher for the authenticated app (the marketing site links straight
// to its hreflang alternate instead, so the swap is crawlable).
Route::get('locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::middleware(['auth'])->group(function () {
    // Generic entry point: route the user to their role's home.
    Route::get('dashboard', [DashboardController::class, 'redirect'])->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    Route::post('logout', function (Logout $logout) {
        $logout();

        return redirect('/');
    })->name('logout');

    /*
    |------------------------------------------------------------------
    | Management / Executive
    |------------------------------------------------------------------
    */
    Route::middleware('role:admin|manager')->group(function () {
        Route::get('executive', [DashboardController::class, 'executive'])->name('executive.dashboard');
    });

    /*
    |------------------------------------------------------------------
    | Back-office (admin / manager / ops)
    |------------------------------------------------------------------
    */
    Route::middleware('role:admin|manager|ops')->group(function () {
        Route::get('operations', [DashboardController::class, 'operations'])->name('operations.dashboard');

        // Modules backed by a Volt component: resources/views/livewire/{slug}/index.blade.php
        $builtModules = [
            'clients' => 'clients.index',
            'workers' => 'workers.index',
            'contracts' => 'contracts.index',
            'deployments' => 'deployments.index',
            'recruitment' => 'recruitment.index',
            'timesheets' => 'timesheets.index',
            'payroll' => 'payroll.index',
            'invoices' => 'invoices.index',
            'compliance' => 'compliance.index',
        ];
        foreach ($builtModules as $slug => $view) {
            Volt::route($slug, $view)->name("$slug.index");
        }
    });

    /*
    |------------------------------------------------------------------
    | Administration (admin only)
    |------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Volt::route('admin/users', 'admin.users')->name('admin.users');
        Volt::route('admin/activity', 'admin.activity')->name('admin.activity');
    });

    /*
    |------------------------------------------------------------------
    | Client portal
    |------------------------------------------------------------------
    */
    Route::middleware('role:client')->prefix('portal')->name('client.')->group(function () {
        Route::get('/', [ClientPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('workers', [ClientPortalController::class, 'workers'])->name('workers');
        Route::get('timesheets', [ClientPortalController::class, 'timesheets'])->name('timesheets');
        Route::get('invoices', [ClientPortalController::class, 'invoices'])->name('invoices');
    });

    /*
    |------------------------------------------------------------------
    | Worker portal
    |------------------------------------------------------------------
    */
    Route::middleware('role:worker')->prefix('me')->name('worker.')->group(function () {
        Route::get('/', [WorkerPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('documents', [WorkerPortalController::class, 'documents'])->name('documents');
        Route::get('payslips', [WorkerPortalController::class, 'payslips'])->name('payslips');
    });
});

require __DIR__.'/auth.php';
