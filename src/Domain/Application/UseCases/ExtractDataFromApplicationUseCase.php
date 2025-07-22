<?php

declare(strict_types=1);

namespace Domain\Application\UseCases;

use App\UseCases\UseCase;
use Domain\AI\Contracts\AIServiceInterface;
use Domain\Application\Models\Application;
use Domain\Application\Repositories\ApplicationRepositoryInterface;
use Domain\Application\Repositories\Input\ApplicationUpdateInput;
use Illuminate\Support\Facades\DB;

class ExtractDataFromApplicationUseCase extends UseCase
{
    public function __construct(
        private readonly ApplicationRepositoryInterface $applicationRepository,
        private readonly AIServiceInterface $AIService
    ) {
    }

    public function handle(Application $application): Application
    {
        $data = $this->AIService->extractCVData($application->cv);

        $input = new ApplicationUpdateInput(
            candidate: null,
            language: $application->language,
            gender: $data->gender,
            source: $application->source,
            firstname: $application->firstname,
            lastname: $application->lastname,
            email: $application->email,
            phonePrefix: $application->phone_prefix,
            phoneNumber: $application->phone_number,
            linkedin: $application->linkedin,
            instagram: $data->instagram,
            github: $data->github,
            portfolio: $data->portfolio,
            birthDate: $data->birthDate,
            experience: $data->experience,
        );

        return DB::transaction(function () use (
            $application,
            $input
        ): Application {
            return $this->applicationRepository->update($application, $input);
        }, attempts: 5);
    }
}
