<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class WebController extends Controller
{
    public function welcome(): View
    {
        return view('welcome');
    }
}
