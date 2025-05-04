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
        /** @var ClassifierRepositoryInterface $repository */
        $repository = app(ClassifierRepositoryInterface::class);

        $result = $repository->getValuesForTypes([
            ClassifierTypeEnum::BENEFIT,
            ClassifierTypeEnum::EMPLOYMENT_TYPE,
            ClassifierTypeEnum::REFUSAL_TYPE,
            ClassifierTypeEnum::REJECTION_TYPE,
            ClassifierTypeEnum::PHONE_PREFIX,
        ]);

        //        dd($result);

        return view('welcome');
    }
}
