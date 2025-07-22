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
use Support\File\Services\FileSaver;

class ApplicationApplyUseCase extends UseCase
{
    public function __construct(
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

            $this->fileSaver->saveFiles(
                fileable: $application,
                type: FileTypeEnum::APPLICATION_CV,
                files: [$data->getCvAsFileData()]
            );

            if ($data->hasOtherFiles()) {
                $this->fileSaver->saveFiles(
                    fileable: $application,
                    type: FileTypeEnum::APPLICATION_OTHER,
                    files: $data->getOtherFilesAsFileData()
                );
            }

            return $application;
        }, attempts: 5);
    }
}
