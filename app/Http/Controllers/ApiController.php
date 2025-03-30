<?php

namespace App\Http\Controllers;

use App\Http\Traits\RespondsAsJson;

abstract class ApiController extends Controller
{
    use RespondsAsJson;
}
