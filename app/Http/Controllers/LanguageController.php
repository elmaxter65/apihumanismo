<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    //
    public function swap($locale)
    {
        // available language in template array
        $availLocale = ['en' => 'en', 'es' => 'es', 'fr' => 'fr', 'de' => 'de', 'pt' => 'pt'];
        // check for existing language
        if (array_key_exists($locale, $availLocale)) {
            session()->put('locale', $locale);
        }
        return redirect()->back();
    }

    public function getLanguagesListJson()
    {
        $languages = Language::all();

        $data = array();

        foreach ($languages as $key => $language) {
            $data[$language->id] = $language->name;
        }

        echo json_encode($data);
    }
}
