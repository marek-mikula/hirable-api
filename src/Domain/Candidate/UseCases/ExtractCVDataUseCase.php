<?php

declare(strict_types=1);

namespace Domain\Candidate\UseCases;

use App\UseCases\UseCase;
use Domain\AI\Context\Transformers\CandidateTransformer;
use Domain\AI\Services\AIService;
use Domain\Candidate\Enums\CandidateFieldEnum;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Repositories\CandidateRepositoryInterface;
use Domain\Candidate\Repositories\Input\CandidateUpdateInput;
use Illuminate\Support\Facades\DB;
use Support\File\Models\File;

final class ExtractCVDataUseCase extends UseCase
{
    public function __construct(
        private readonly CandidateRepositoryInterface $candidateRepository,
        private readonly CandidateTransformer $candidateTransformer,
        private readonly AIService $AIService
    ) {
    }

    public function handle(Candidate $candidate): Candidate
    {
        /** @var File|null $cv */
        $cv = $candidate->cvs()->first();

        throw_if(empty($cv), new \InvalidArgumentException(sprintf('Missing CV of candidate %d.', $candidate->id)));

        $attributes = $this->AIService->extractCVData($cv);

        $attributes = collect($attributes)
            ->filter(fn (mixed $value, string $key): bool => CandidateFieldEnum::tryFrom($key) !== null)
            ->map(fn (mixed $value, string $key): mixed => $this->candidateTransformer->transformField($key, $value))
            ->toArray();

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

        return DB::transaction(fn (): Candidate => $this->candidateRepository->update($candidate, $input), attempts: 5);
    }
}
