<?php

declare(strict_types=1);

namespace Domain\Candidate\UseCases;

use App\Enums\LanguageEnum;
use App\UseCases\UseCase;
use Domain\AI\Context\Transformers\CandidateTransformer;
use Domain\AI\Services\AIService;
use Domain\Candidate\Enums\CandidateFieldEnum;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Repositories\CandidateRepositoryInterface;
use Domain\Candidate\Repositories\Input\CandidateStoreInput;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Input\PositionCandidateStoreInput;
use Domain\Position\Repositories\PositionCandidateRepositoryInterface;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Domain\ProcessStep\Enums\StepEnum;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Support\File\Models\File;
use Support\File\Repositories\ModelHasFileRepositoryInterface;
use Support\File\Services\FileMover;
use Support\File\Services\FilePathService;

final class CreateCandidateFromCvUseCase extends UseCase
{
    public function __construct(
        private readonly PositionProcessStepRepositoryInterface $positionProcessStepRepository,
        private readonly PositionCandidateRepositoryInterface $positionCandidateRepository,
        private readonly ModelHasFileRepositoryInterface $modelHasFileRepository,
        private readonly CandidateRepositoryInterface $candidateRepository,
        private readonly CandidateTransformer $candidateTransformer,
        private readonly FilePathService $filePathService,
        private readonly FileMover $fileMover,
        private readonly AIService $AIService,
    ) {
    }

    public function handle(
        User $user,
        File $cv,
        Position|null $position,
    ): void {
        $attributes = $this->AIService->extractCVData($cv);

        $attributes = collect($attributes)
            ->filter(fn (mixed $value, string $key): bool => CandidateFieldEnum::tryFrom($key) !== null)
            ->map(fn (mixed $value, string $key): mixed => $this->candidateTransformer->transformField($key, $value))
            ->all();

        $hasRequiredFields = Arr::has($attributes, [
            CandidateFieldEnum::FIRSTNAME->value,
            CandidateFieldEnum::LASTNAME->value,
            CandidateFieldEnum::EMAIL->value,
            CandidateFieldEnum::PHONE_PREFIX->value,
            CandidateFieldEnum::PHONE_NUMBER->value,
        ]);

        if (!$hasRequiredFields) {
            // todo send failed notification

            return;
        }

        $input = new CandidateStoreInput(
            company: $user->company,
            language: $attributes[CandidateFieldEnum::LANGUAGE->value] ?? LanguageEnum::CS,
            firstname: $attributes[CandidateFieldEnum::FIRSTNAME->value],
            lastname: $attributes[CandidateFieldEnum::LASTNAME->value],
            email: $attributes[CandidateFieldEnum::EMAIL->value],
            phonePrefix: $attributes[CandidateFieldEnum::PHONE_PREFIX->value],
            phoneNumber: $attributes[CandidateFieldEnum::PHONE_NUMBER->value],
            gender: $attributes[CandidateFieldEnum::GENDER->value] ?? null,
            linkedin: $attributes[CandidateFieldEnum::LINKEDIN->value] ?? null,
            instagram: $attributes[CandidateFieldEnum::INSTAGRAM->value] ?? null,
            github: $attributes[CandidateFieldEnum::GITHUB->value] ?? null,
            portfolio: $attributes[CandidateFieldEnum::PORTFOLIO->value] ?? null,
            birthDate: $attributes[CandidateFieldEnum::BIRTH_DATE->value] ?? null,
            experience: $attributes[CandidateFieldEnum::EXPERIENCE->value] ?? [],
            tags: $attributes[CandidateFieldEnum::TAGS->value] ?? [],
        );

        DB::transaction(function () use (
            $input,
            $position,
            $cv,
        ): void {
            /** @var Candidate $candidate */
            $candidate = Candidate::withoutEvents(fn () => $this->candidateRepository->store($input));

            if ($position !== null) {
                $positionProcessStep = $this->positionProcessStepRepository->findByPosition($position, StepEnum::NEW);

                throw_if(empty($positionProcessStep), new \Exception('Missing default position process step.'));

                $this->positionCandidateRepository->store(new PositionCandidateStoreInput(
                    $position,
                    $candidate,
                    null,
                    $positionProcessStep,
                ));
            }

            // transfer CV to candidate
            $cv = $this->fileMover->moveFile(
                file: $cv,
                path: $this->filePathService->getPathForModel($candidate),
            );

            $this->modelHasFileRepository->store($candidate, $cv);

            // todo send success notification
        }, attempts: 5);
    }
}
