<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\CompanyContactRepositoryInterface;
use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Http\Request\Data\PositionData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Repositories\Inputs\PositionStoreInput;
use Domain\Position\Repositories\ModelHasPositionRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\Position\Services\PositionApprovalService;
use Domain\User\Models\User;
use Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Support\File\Actions\GetModelSubFoldersAction;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Services\FileSaver;

class PositionStoreUseCase extends UseCase
{
    public function __construct(
        private readonly ModelHasPositionRepositoryInterface $modelHasPositionRepository,
        private readonly CompanyContactRepositoryInterface $companyContactRepository,
        private readonly PositionApprovalService $positionApprovalService,
        private readonly PositionRepositoryInterface $positionRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly FileSaver $fileSaver,
    ) {
    }

    public function handle(User $user, PositionData $data): Position
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
            approveUntil: $data->approveUntil,
            approvalRound: null,
            name: $data->name,
            department: $data->department,
            field: $data->field,
            jobSeatsNum: $data->jobSeatsNum,
            description: $data->description,
            isTechnical: $data->isTechnical,
            address: $data->address,
            salaryFrom: (int) ($data->salary ?? $data->salaryFrom),
            salaryTo: $data->salary ? null : $data->salaryTo,
            salaryType: $data->salaryType,
            salaryFrequency: $data->salaryFrequency,
            salaryCurrency: $data->salaryCurrency,
            salaryVar: $data->salaryVar,
            minEducationLevel: $data->minEducationLevel,
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
            languageRequirements: array_map(fn ($requirement) => [
                'language' => $requirement->language,
                'level' => $requirement->level,
            ], $data->languageRequirements),
        );

        $hiringManagers = $data->hasHiringManagers()
            ? $this->userRepository->getByIdsAndCompany($company, $data->hiringManagers)
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
            $approvers,
            $externalApprovers,
        ): Position {
            $position = $this->positionRepository->store($input);

            if ($position->state !== $state) {
                $position = $this->positionRepository->updateState($position, $state);
            }

            $this->processHiringManagers($position, $hiringManagers);
            $this->processApprovers($position, $approvers);
            $this->processExternalApprovers($position, $externalApprovers);
            $this->processFiles($position, $data);

            if ($position->state === PositionStateEnum::APPROVAL_PENDING) {
                $approvals = $this->positionApprovalService->sendForApproval($user, $position);
                $position->setRelation('approvals', $approvals);
            } else {
                $position->setRelation('approvals', modelCollection(PositionApproval::class));
            }

            return $position;
        }, attempts: 5);
    }

    private function processHiringManagers(Position $position, Collection $hiringManagers): void
    {
        if ($hiringManagers->isEmpty()) {
            return;
        }

        $this->modelHasPositionRepository->storeMany($position, $hiringManagers, PositionRoleEnum::HIRING_MANAGER);

        $position->setRelation('hiringManagers', $hiringManagers);
    }

    private function processApprovers(Position $position, Collection $approvers): void
    {
        if ($approvers->isEmpty()) {
            return;
        }

        $this->modelHasPositionRepository->storeMany($position, $approvers, PositionRoleEnum::APPROVER);

        $position->setRelation('approvers', $approvers);
    }

    private function processExternalApprovers(Position $position, Collection $externalApprovers): void
    {
        if ($externalApprovers->isEmpty()) {
            return;
        }

        $this->modelHasPositionRepository->storeMany($position, $externalApprovers, PositionRoleEnum::EXTERNAL_APPROVER);

        $position->setRelation('externalApprovers', $externalApprovers);
    }

    private function processFiles(Position $position, PositionData $data): void
    {
        if (!$data->hasFiles()) {
            $position->setRelation('files', modelCollection(File::class));

            return;
        }

        $files = $this->fileSaver->saveFiles(
            fileable: $position,
            type: FileTypeEnum::POSITION_FILE,
            files: $data->getFilesData(),
            folders: GetModelSubFoldersAction::make()->handle($position)
        );

        $position->setRelation('files', $files);
    }
}
