<?php

declare(strict_types=1);

namespace Domain\Candidate\UseCases;

use App\UseCases\UseCase;
use Domain\AI\Contracts\AIServiceInterface;
use Domain\AI\Data\CVDataExperience;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Repositories\CandidateRepositoryInterface;
use Domain\Candidate\Repositories\Input\CandidateUpdateInput;
use Illuminate\Support\Facades\DB;

class ExtractCVDataUseCase extends UseCase
{
    public function __construct(
        private readonly CandidateRepositoryInterface $candidateRepository,
        private readonly AIServiceInterface $AIService
    ) {
    }

    public function handle(Candidate $candidate): Candidate
    {
        $data = $this->AIService->extractCVData($candidate->cv);

        $input = new CandidateUpdateInput(
            language: $candidate->language,
            gender: $data->gender,
            firstname: $candidate->firstname,
            lastname: $candidate->lastname,
            email: $candidate->email,
            phonePrefix: $candidate->phone_prefix,
            phoneNumber: $candidate->phone_number,
            linkedin: $candidate->linkedin,
            instagram: $data->instagram,
            github: $data->github,
            portfolio: $data->portfolio,
            birthDate: $data->birthDate,
            experience: array_map(fn (CVDataExperience $item) => $item->toArray(), $data->experience),
        );

        return DB::transaction(function () use (
            $candidate,
            $input
        ): Candidate {
            return $this->candidateRepository->update($candidate, $input);
        }, attempts: 5);
    }
}
