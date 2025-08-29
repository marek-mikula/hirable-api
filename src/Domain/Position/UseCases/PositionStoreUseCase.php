<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\CompanyContactRepositoryInterface;
use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Http\Request\Data\LanguageRequirementData;
use Domain\Position\Http\Request\Data\PositionStoreData;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Inputs\PositionStoreInput;
use Domain\Position\Repositories\ModelHasPositionRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;
use Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Repositories\ModelHasFileRepositoryInterface;
use Support\File\Services\FilePathService;
use Support\File\Services\FileSaver;

class PositionStoreUseCase extends UseCase
{
    public function __construct(
        private readonly ModelHasPositionRepositoryInterface $modelHasPositionRepository,
        private readonly CompanyContactRepositoryInterface $companyContactRepository,
        private readonly ModelHasFileRepositoryInterface $modelHasFileRepository,
        private readonly PositionRepositoryInterface $positionRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly FilePathService $filePathService,
        private readonly FileSaver $fileSaver,
    ) {
    }

    public function handle(User $user, PositionStoreData $data): Position
    {
        $company = $user->loadMissing('company')->company;

        $state = match ($data->operation) {
            PositionOperationEnum::SEND_FOR_APPROVAL => PositionStateEnum::APPROVAL_PENDING,
            PositionOperationEnum::OPEN => PositionStateEnum::OPENED,
            PositionOperationEnum::SAVE => PositionStateEnum::DRAFT,
        };

        $input = new PositionStoreInput(
            company: $company,
            user: $user,
            name: $data->name,
            externName: $data->externName,
            approveUntil: $data->approveUntil,
            approveMessage: $data->approveMessage,
            department: $data->department,
            field: $data->field,
            jobSeatsNum: $data->jobSeatsNum,
            description: $data->description,
            address: $data->address,
            salaryFrom: (int) ($data->salary ?? $data->salaryFrom),
            salaryTo: $data->salary ? null : $data->salaryTo,
            salaryType: $data->salaryType,
            salaryFrequency: $data->salaryFrequency,
            salaryCurrency: $data->salaryCurrency,
            salaryVar: $data->salaryVar,
            minEducationLevel: $data->minEducationLevel,
            educationField: $data->educationField,
            seniority: $data->seniority,
            experience: $data->experience,
            hardSkills: $data->hardSkills,
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
            languageRequirements: array_map(fn (LanguageRequirementData $requirement) => $requirement->toArray(), $data->languageRequirements),
            hardSkillsWeight: $data->hardSkillsWeight,
            softSkillsWeight: $data->softSkillsWeight,
            languageSkillsWeight: $data->languageSkillsWeight,
            experienceWeight: $data->experienceWeight,
            educationWeight: $data->educationWeight,
            shareSalary: $data->shareSalary,
            shareContact: $data->shareContact,
            tags: $data->tags,
        );

        $hiringManagers = $data->hasHiringManagers()
            ? $this->userRepository->getByIdsAndCompany($company, $data->hiringManagers)
            : modelCollection(User::class);

        $recruiters = $data->hasRecruiters()
            ? $this->userRepository->getByIdsAndCompany($company, $data->recruiters)
            : modelCollection(User::class);

        $approvers = $data->hasApprovers()
            ? $this->userRepository->getByIdsAndCompany($company, $data->approvers)
            : modelCollection(User::class);

        $externalApprovers = $data->hasExternalApprovers()
            ? $this->companyContactRepository->getByIdsAndCompany($company, $data->externalApprovers)
            : modelCollection(CompanyContact::class);

        return DB::transaction(function () use (
            $data,
            $input,
            $state,
            $user,
            $hiringManagers,
            $recruiters,
            $approvers,
            $externalApprovers,
        ): Position {
            $position = $this->positionRepository->store($input);

            // process models and files
            $this->processModels($position, $hiringManagers, PositionRoleEnum::HIRING_MANAGER, 'hiringManagers');
            $this->processModels($position, $recruiters, PositionRoleEnum::RECRUITER, 'recruiters');
            $this->processModels($position, $approvers, PositionRoleEnum::APPROVER, 'approvers');
            $this->processModels($position, $externalApprovers, PositionRoleEnum::EXTERNAL_APPROVER, 'externalApprovers');
            $this->processFiles($position, $data);

            // process state
            if ($position->state !== $state) {
                $position = $this->positionRepository->updateState($position, $state);
            }

            return $position;
        }, attempts: 5);
    }

    /**
     * @param Collection<Model> $models
     */
    private function processModels(Position $position, Collection $models, PositionRoleEnum $role, string $relation): void
    {
        if ($models->isEmpty()) {
            return;
        }

        $this->modelHasPositionRepository->storeMany($position, $models, $role);

        $position->setRelation($relation, $models);
    }

    private function processFiles(Position $position, PositionStoreData $data): void
    {
        if (!$data->hasFiles()) {
            $position->setRelation('files', modelCollection(File::class));

            return;
        }

        $files = modelCollection(File::class);

        foreach ($data->getFilesData() as $fileData) {
            $file = $this->fileSaver->saveFile(
                file: $fileData,
                path: $this->filePathService->getPathForModel($position),
                type: FileTypeEnum::POSITION_FILE,
            );

            $this->modelHasFileRepository->store($position, $file);

            $files->push($file);
        }

        $position->setRelation('files', $files);
    }
}
