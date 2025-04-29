<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Traits\RespondsAsJson;

abstract class ApiController extends Controller
{
    use RespondsAsJson;
}
