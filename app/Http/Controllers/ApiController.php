<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\RespondsAsJson;

abstract class ApiController extends Controller
{
    use RespondsAsJson;
}
