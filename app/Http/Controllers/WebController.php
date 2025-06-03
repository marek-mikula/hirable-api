<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

class WebController extends Controller
{
    public function welcome(): View
    {
        //                User::factory()->count(20)->create(['company_id' => 1]);
        //                CompanyContact::factory()->count(20)->create(['company_id' => 1]);

        return view('welcome');
    }
}
