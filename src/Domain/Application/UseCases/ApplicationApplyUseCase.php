<?php

declare(strict_types=1);

namespace Domain\Application\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
use Domain\Application\Data\ApplyData;
use Domain\Application\Models\Application;
use Domain\Application\Repositories\ApplicationRepositoryInterface;
use Domain\Application\Repositories\Input\ApplicationStoreInput;
use Domain\Application\TokenProcessing\Data\TokenPackage;
use Illuminate\Support\Facades\DB;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Repositories\ModelHasFileRepositoryInterface;
use Support\File\Services\FileSaver;

class ApplicationApplyUseCase extends UseCase
{
    public function __construct(
        private readonly ModelHasFileRepositoryInterface $modelHasFileRepository,
        private readonly ApplicationRepositoryInterface $applicationRepository,
        private readonly FileSaver $fileSaver,
    ) {
    }

    public function handle(TokenPackage $tokenPackage, ApplyData $data): Application
    {
        $existsDuplicate = $this->applicationRepository->existsDuplicateOnPosition(
            position: $tokenPackage->tokenData->position,
            email: $data->email,
            phonePrefix: $data->phonePrefix,
            phoneNumber: $data->phoneNumber,
        );

        throw_if($existsDuplicate, new HttpException(responseCode: ResponseCodeEnum::APPLICATION_DUPLICATE));

        $input = new ApplicationStoreInput(
            position: $tokenPackage->tokenData->position,
            language: appLocale(),
            source: $tokenPackage->tokenInfo->source,
            firstname: $data->firstname,
            lastname: $data->lastname,
            email: $data->email,
            phonePrefix: $data->phonePrefix,
            phoneNumber: $data->phoneNumber,
            linkedin: $data->linkedin,
        );

        return DB::transaction(function () use ($data, $input): Application {
            $application = $this->applicationRepository->store($input);

            $this->saveFiles($application, $data);

            return $application;
        }, attempts: 5);
    }

    private function saveFiles(Application $application, ApplyData $data): void
    {
        $files = modelCollection(File::class);

        $cv = $this->fileSaver->saveFile(
            file: $data->getCvAsFileData(),
            path: 'applications',
            type: FileTypeEnum::CANDIDATE_CV
        );

        $this->modelHasFileRepository->store($application, $cv);

        $files->push($cv);

        if ($data->hasOtherFiles()) {
            foreach ($data->getOtherFilesAsFileData() as $fileData) {
                $otherFile = $this->fileSaver->saveFile(
                    file: $fileData,
                    path: 'applications',
                    type: FileTypeEnum::CANDIDATE_OTHER,
                );

                $this->modelHasFileRepository->store($application, $otherFile);

                $files->push($otherFile);
            }
        }

        $application->setRelation('files', $files);
    }
}
