<?php

declare(strict_types=1);

namespace Domain\Application\Jobs;

use App\Jobs\CommonJob;
use Domain\AI\Contracts\AIServiceInterface;
use Domain\Application\Models\Application;
use Domain\Application\Repositories\ApplicationRepositoryInterface;
use Domain\Application\Repositories\Input\ApplicationUpdateInput;
use Illuminate\Support\Facades\DB;

class ExtractDataFromApplicationJob extends CommonJob
{
    public function __construct(
        private readonly Application $application,
    ) {
        parent::__construct();
    }

    public function handle(
        AIServiceInterface $AIService,
        ApplicationRepositoryInterface $applicationRepository,
    ): void {
        $data = $AIService->extractCVData($this->application->cv);

        $input = new ApplicationUpdateInput(
            candidate: null,
            language: $this->application->language,
            gender: $data->gender,
            source: $this->application->source,
            firstname: $this->application->firstname,
            lastname: $this->application->lastname,
            email: $this->application->email,
            phonePrefix: $this->application->phone_prefix,
            phoneNumber: $this->application->phone_number,
            linkedin: $this->application->linkedin,
            instagram: $data->instagram,
            github: $data->github,
            portfolio: $data->portfolio,
            birthDate: $data->birthDate,
            experience: $data->experience,
        );

        DB::transaction(function () use (
            $applicationRepository,
            $input
        ): void {
            $application = $applicationRepository->update($this->application, $input);
            ScoreApplicationJob::dispatch($application);
        }, attempts: 5);
    }
}
