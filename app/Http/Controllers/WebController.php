<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Repositories\ClassifierRepositoryInterface;

class WebController extends Controller
{
    public function welcome(): View
    {
        /** @var ClassifierRepositoryInterface $repo */
        $repo = app(ClassifierRepositoryInterface::class);

        dd($repo->getValuesForType(ClassifierTypeEnum::PHONE_PREFIX)->pluck('label')->toArray());

        return view('welcome');
    }
}
