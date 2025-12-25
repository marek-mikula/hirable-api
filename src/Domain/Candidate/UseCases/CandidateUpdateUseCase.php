<?php

declare(strict_types=1);

namespace Domain\Candidate\UseCases;

use App\UseCases\UseCase;
use Domain\Candidate\Http\Request\Data\CandidateUpdateData;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Repositories\CandidateRepositoryInterface;
use Domain\Candidate\Repositories\Input\CandidateUpdateInput;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Support\File\Data\FileData;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Repositories\FileRepositoryInterface;
use Support\File\Repositories\ModelHasFileRepositoryInterface;
use Support\File\Services\FilePathService;
use Support\File\Services\FileSaver;

final class CandidateUpdateUseCase extends UseCase
{
    public function __construct(
        private readonly ModelHasFileRepositoryInterface $modelHasFileRepository,
        private readonly CandidateRepositoryInterface $candidateRepository,
        private readonly FileRepositoryInterface $fileRepository,
        private readonly FilePathService $filePathService,
        private readonly FileSaver $fileSaver,
    ) {
    }

    public function handle(User $user, Candidate $candidate, CandidateUpdateData $data): Candidate
    {
        $input = new CandidateUpdateInput(
            language: $data->hasKey('language') ? $data->language : $candidate->language,
            gender: $data->hasKey('gender') ? $data->gender : $candidate->gender,
            firstname: $data->hasKey('firstname') ? $data->firstname : $candidate->firstname,
            lastname: $data->hasKey('lastname') ? $data->lastname : $candidate->lastname,
            email: $data->hasKey('email') ? $data->email : $candidate->email,
            phonePrefix: $data->hasKey('phone') ? $data->phonePrefix : $candidate->phone_prefix,
            phoneNumber: $data->hasKey('phone') ? $data->phoneNumber : $candidate->phone_number,
            linkedin: $data->hasKey('linkedin') ? $data->linkedin : $candidate->linkedin,
            instagram: $data->hasKey('instagram') ? $data->instagram : $candidate->instagram,
            github: $data->hasKey('github') ? $data->github : $candidate->github,
            portfolio: $data->hasKey('portfolio') ? $data->portfolio : $candidate->portfolio,
            birthDate: $data->hasKey('birthDate') ? $data->birthDate : $candidate->birth_date,
            experience: $candidate->experience,
            tags: $data->hasKey('tags') ? $data->tags : $candidate->tags,
        );

        return DB::transaction(function () use (
            $candidate,
            $input,
            $data,
        ): Candidate {
            $candidate =  $this->candidateRepository->update($candidate, $input);

            if ($data->hasKey('cv') && $data->cv) {
                /** @var File|null $previousCv */
                $previousCv = $candidate->files->first(fn (File $file): bool => $file->type === FileTypeEnum::CANDIDATE_CV);

                // move previously saved CV to other files
                if ($previousCv) {
                    $this->fileRepository->updateType($previousCv, FileTypeEnum::CANDIDATE_OTHER);
                }

                $cv = $this->fileSaver->saveFile(
                    file: FileData::make($data->cv),
                    path: $this->filePathService->getPathForModel($candidate),
                    type: FileTypeEnum::CANDIDATE_CV
                );

                $this->modelHasFileRepository->store($candidate, $cv);

                $candidate->files->push($cv);
            }

            if ($data->hasKey('otherFiles') && !empty($data->otherFiles)) {
                foreach ($data->otherFiles as $otherFile) {
                    $cv = $this->fileSaver->saveFile(
                        file: FileData::make($otherFile),
                        path: $this->filePathService->getPathForModel($candidate),
                        type: FileTypeEnum::CANDIDATE_OTHER
                    );

                    $this->modelHasFileRepository->store($candidate, $cv);
                }
            }

            return $candidate;
        }, attempts: 5);
    }
}
