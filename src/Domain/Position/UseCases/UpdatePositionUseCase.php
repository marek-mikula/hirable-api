<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Repositories\CompanyContactRepositoryInterface;
use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Http\Request\Data\PositionData;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Inputs\PositionUpdateInput;
use Domain\Position\Repositories\ModelHasPositionRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;
use Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Support\File\Actions\GetModelSubFoldersAction;
use Support\File\Enums\FileTypeEnum;
use Support\File\Services\FileSaver;

class UpdatePositionUseCase extends UseCase
{
    public function __construct(
        private readonly ModelHasPositionRepositoryInterface $modelHasPositionRepository,
        private readonly CompanyContactRepositoryInterface $companyContactRepository,
        private readonly PositionRepositoryInterface $positionRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly FileSaver $fileSaver,
    ) {
    }

    public function handle(User $user, Position $position, PositionData $data): Position
    {
        if ($data->hasFiles()) {
            $position->loadMissing('files');
        }

        $company = $user->loadMissing('company')->company;

        $position->loadMissing([
            'hiringManagers',
            'approvers',
            'externalApprovers',
        ]);

        $input = new PositionUpdateInput(
            state: $data->operation === PositionOperationEnum::OPEN ? PositionStateEnum::OPENED : $position->state,
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
            drivingLicences: $data->drivingLicences,
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

        $hiringManagers = $data->hasHiringManagers()
            ? $this->userRepository->getByIdsAndCompany($company, $data->hiringManagers)
            : null;

        $approvers = $data->hasApprovers()
            ? $this->userRepository->getByIdsAndCompany($company, $data->approvers)
            : null;

        $externalApprovers = $data->hasExternalApprovers()
            ? $this->companyContactRepository->getByIdsAndCompany($company, $data->externalApprovers)
            : null;

        return DB::transaction(function () use (
            $user,
            $position,
            $data,
            $input,
            $hiringManagers,
            $approvers,
            $externalApprovers,
        ): Position {
            $position = $this->positionRepository->update($position, $input);

            $this->modelHasPositionRepository->sync($position, $position->hiringManagers, $hiringManagers, PositionRoleEnum::HIRING_MANAGER);
            $position->setRelation('hiringManagers', $hiringManagers);

            $this->modelHasPositionRepository->sync($position, $position->approvers, $approvers, PositionRoleEnum::APPROVER);
            $position->setRelation('approvers', $approvers);

            $this->modelHasPositionRepository->sync($position, $position->externalApprovers, $externalApprovers, PositionRoleEnum::APPROVER);
            $position->setRelation('externalApprovers', $externalApprovers);

            // save files if any
            if ($data->hasFiles()) {
                $files = $this->fileSaver->saveFiles(
                    fileable: $position,
                    type: FileTypeEnum::POSITION_FILE,
                    files: $data->getFilesData(),
                    folders: GetModelSubFoldersAction::make()->handle($position)
                );

                $position->setRelation('files', $position->files->push(...$files));
            }

            return $position;
        }, attempts: 5);
    }
}
