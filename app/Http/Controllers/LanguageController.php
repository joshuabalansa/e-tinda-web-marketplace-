<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLanguage($locale)
    {
        // Check if the locale is supported
        if (in_array($locale, ['en', 'hil'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);

            // Set cookie for persistence across sessions
            return redirect()->back()->withCookie(cookie('locale', $locale, 60 * 24 * 30)); // 30 days
        }

        return redirect()->back();
    }
}
