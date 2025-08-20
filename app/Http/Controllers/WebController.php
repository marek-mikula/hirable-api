<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Domain\AI\Context\ModelContexter;
use Domain\Position\Models\Position;
use Illuminate\View\View;

class WebController extends Controller
{
    public function welcome(): View
    {
        /** @var ModelContexter $contexter */
        $contexter = app(ModelContexter::class);

        dd($contexter->getModelContext(Position::class));

        return view('welcome');
    }
}
