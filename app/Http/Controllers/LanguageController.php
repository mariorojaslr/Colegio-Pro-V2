<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Cambiar el idioma de la aplicación.
     */
    public function switch($lang)
    {
        if (in_array($lang, ['es', 'en', 'pt'])) {
            Session::put('app_locale', $lang);
        }
        return redirect()->back();
    }
}
