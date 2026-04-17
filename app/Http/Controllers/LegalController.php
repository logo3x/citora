<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class LegalController extends Controller
{
    public function privacy(): View
    {
        return view('legal.privacy', ['legal' => config('legal')]);
    }

    public function terms(): View
    {
        return view('legal.terms', ['legal' => config('legal')]);
    }
}
