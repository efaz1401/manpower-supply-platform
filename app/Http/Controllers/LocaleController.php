<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /** Switch the active locale and persist it to the session (and user). */
    public function switch(Request $request, string $locale)
    {
        if (array_key_exists($locale, config('app.supported_locales', []))) {
            session(['locale' => $locale]);

            if ($request->user()) {
                $request->user()->forceFill(['locale' => $locale])->save();
            }
        }

        return redirect()->back();
    }
}
