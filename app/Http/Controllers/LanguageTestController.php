<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageTestController extends Controller
{
    public function index()
    {
        return view('language-test');
    }
}
