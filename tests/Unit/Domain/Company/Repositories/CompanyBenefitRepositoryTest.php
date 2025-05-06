<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Company\Repositories;

use Domain\Company\Repositories\CompanyBenefitRepositoryInterface;

/** @covers \Domain\Company\Repositories\CompanyBenefitRepository::find */
it('tests find method', function (): void {
    /** @var CompanyBenefitRepositoryInterface $repository */
    $repository = app(CompanyBenefitRepositoryInterface::class);
})->todo();
