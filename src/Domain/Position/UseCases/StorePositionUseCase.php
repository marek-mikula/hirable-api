<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Http\Request\Data\PositionStoreData;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Inputs\PositionStoreInput;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Support\File\Actions\GetModelSubFoldersAction;
use Support\File\Enums\FileTypeEnum;
use Support\File\Services\FileSaver;

class StorePositionUseCase extends UseCase
{
    public function __construct(
        private readonly PositionRepositoryInterface $positionRepository,
        private readonly FileSaver $fileSaver,
    ) {
    }

    public function handle(User $user, PositionStoreData $data): Position
    {
        $input = new PositionStoreInput(
            company: $user->loadMissing('company')->company,
            user: $user,
            state: $data->operation === 'open' ? PositionStateEnum::OPENED : PositionStateEnum::DRAFT,
            name: $data->name,
            department: $data->department,
            field: $data->field,
            jobSeatsNum: $data->jobSeatsNum,
            description: $data->description,
            isTechnical: $data->isTechnical,
            address: $data->address,
            salaryFrom: $data->salaryFrom,
            salaryTo: $data->salaryTo,
            salary: $data->salary,
            salaryType: $data->salaryType,
            salaryFrequency: $data->salaryFrequency,
            salaryCurrency: $data->salaryCurrency,
            salaryVar: $data->salaryVar,
            minEducationLevel: $data->minEducationLevel,
            seniority: $data->seniority,
            experience: $data->experience,
            drivingLicence: $data->drivingLicence,
            organisationSkills: $data->organisationSkills,
            teamSkills: $data->teamSkills,
            timeManagement: $data->timeManagement,
            communicationSkills: $data->communicationSkills,
            leadership: $data->leadership,
            note: $data->note,
            workloads: $data->workloads,
            employmentRelationships: $data->employmentRelationships,
            employmentForms: $data->employmentForms,
            benefits: $data->benefits,
            languageRequirements: array_map(fn ($requirement) => [
                'language' => $requirement->language,
                'level' => $requirement->level,
            ], $data->languageRequirements),
        );

        return DB::transaction(function () use (
            $data,
            $input,
            $user,
        ): Position {
            $position = $this->positionRepository->store($input);

            // save files if any
            if ($data->hasFiles()) {
                $this->fileSaver->saveFiles(
                    fileable: $position,
                    type: FileTypeEnum::POSITION_FILE,
                    files: $data->getFilesData(),
                    folders: GetModelSubFoldersAction::make()->handle($position)
                );
            }

            return $position;
        }, attempts: 5);
    }
}
