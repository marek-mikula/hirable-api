<?php

declare(strict_types=1);

namespace Domain\Candidate\UseCases;

use App\UseCases\UseCase;
use Domain\AI\Context\Transformers\CandidateTransformer;
use Domain\AI\Contracts\AIServiceInterface;
use Domain\Candidate\Enums\CandidateFieldEnum;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Repositories\CandidateRepositoryInterface;
use Domain\Candidate\Repositories\Input\CandidateUpdateInput;
use Illuminate\Support\Facades\DB;
use Support\File\Models\File;

class ExtractCVDataUseCase extends UseCase
{
    public function __construct(
        private readonly CandidateRepositoryInterface $candidateRepository,
        private readonly CandidateTransformer $candidateTransformer,
        private readonly AIServiceInterface $AIService
    ) {
    }

    public function handle(Candidate $candidate): Candidate
    {
        /** @var File|null $cv */
        $cv = $candidate->cvs()->first();

        throw_if(empty($cv), new \InvalidArgumentException(sprintf('Missing CV of candidate %d.', $candidate->id)));

        $attributes = $this->AIService->extractCVData($cv);

        collect($attributes)
            ->filter(function (mixed $value, string $key): bool { // filter invalid keys
                return CandidateFieldEnum::tryFrom($key) !== null;
            })
            ->map(function (mixed $value, string $key): mixed {
                return $this->candidateTransformer->transformField($key, $value);
            })
            ->all();

        $input = new CandidateUpdateInput(
            language: $candidate->language,
            gender: $attributes[CandidateFieldEnum::GENDER->value] ?? null,
            firstname: $candidate->firstname,
            lastname: $candidate->lastname,
            email: $candidate->email,
            phonePrefix: $candidate->phone_prefix,
            phoneNumber: $candidate->phone_number,
            linkedin: $candidate->linkedin,
            instagram: $attributes[CandidateFieldEnum::INSTAGRAM->value] ?? null,
            github: $attributes[CandidateFieldEnum::GITHUB->value] ?? null,
            portfolio: $attributes[CandidateFieldEnum::PORTFOLIO->value] ?? null,
            birthDate: $attributes[CandidateFieldEnum::BIRTH_DATE->value] ?? null,
            experience: $attributes[CandidateFieldEnum::EXPERIENCE->value] ?? [],
            tags: $attributes[CandidateFieldEnum::TAGS->value] ?? [],
        );

        return DB::transaction(function () use (
            $candidate,
            $input
        ): Candidate {
            return $this->candidateRepository->update($candidate, $input);
        }, attempts: 5);
    }
}
