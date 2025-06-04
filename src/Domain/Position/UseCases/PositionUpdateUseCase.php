<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\CompanyContactRepositoryInterface;
use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Events\PositionOpenedEvent;
use Domain\Position\Http\Request\Data\PositionData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Repositories\Inputs\PositionUpdateInput;
use Domain\Position\Repositories\ModelHasPositionRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\Position\Services\PositionApprovalService;
use Domain\User\Models\User;
use Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Support\File\Actions\GetModelSubFoldersAction;
use Support\File\Enums\FileTypeEnum;
use Support\File\Services\FileSaver;

class PositionUpdateUseCase extends UseCase
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

    public function handle(User $user, Position $position, PositionData $data): Position
    {
        $company = $user->loadMissing('company')->company;

        $position->loadMissing([
            'files',
            'approvals',
            'approvals.modelHasPosition',
            'approvals.modelHasPosition.model',
            'hiringManagers',
            'approvers',
            'externalApprovers',
        ]);

        $input = new PositionUpdateInput(
            state: match ($data->operation) {
                PositionOperationEnum::SEND_FOR_APPROVAL => PositionStateEnum::APPROVAL_PENDING,
                PositionOperationEnum::OPEN => PositionStateEnum::OPENED,
                PositionOperationEnum::SAVE => $data->hasAnyApprovers() ? $position->state : PositionStateEnum::DRAFT,
            },
            approvalRound: null,
            approveUntil:  $data->approveUntil,
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
            : modelCollection(User::class);

        $approvers = $data->hasApprovers()
            ? $this->userRepository->getByIdsAndCompany($company, $data->approvers)
            : modelCollection(User::class);

        $externalApprovers = $data->hasExternalApprovers()
            ? $this->companyContactRepository->getByIdsAndCompany($company, $data->externalApprovers)
            : modelCollection(CompanyContact::class);

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

            $this->processHiringManagers($position, $hiringManagers);
            $this->processApprovers($position, $approvers);
            $this->processExternalApprovers($position, $externalApprovers);
            $this->processFiles($position, $data);

            if ($position->state === PositionStateEnum::APPROVAL_PENDING) {
                $approvals = $this->positionApprovalService->sendForApproval($user, $position);
                $position->setRelation('approvals', $position->approvals->push(...$approvals));
            } elseif ($position->state === PositionStateEnum::OPENED) {
                PositionOpenedEvent::dispatch($position);
            }

            return $position;
        }, attempts: 5);
    }

    private function processHiringManagers(Position $position, Collection $hiringManagers): void
    {
        $hmsSync = $this->modelHasPositionRepository->sync(
            position: $position,
            existingModels: $position->hiringManagers,
            models: $hiringManagers,
            role: PositionRoleEnum::HIRING_MANAGER
        );

        $position->setRelation('hiringManagers', $hiringManagers);

        if ($hmsSync->deleted->isEmpty()) {
            return;
        }

        $newApprovals = $position->approvals->filter(function (PositionApproval $approval) use ($hmsSync) {
            return $approval->modelHasPosition && !$hmsSync->deleted->some(fn (Model $model) => $model->is($approval->modelHasPosition->model));
        });

        $position->setRelation('approvals', $newApprovals);
    }

    private function processApprovers(Position $position, Collection $approvers): void
    {
        $approversSync = $this->modelHasPositionRepository->sync(
            position: $position,
            existingModels: $position->approvers,
            models: $approvers,
            role: PositionRoleEnum::APPROVER
        );

        $position->setRelation('approvers', $approvers);

        if ($approversSync->deleted->isEmpty()) {
            return;
        }

        $newApprovals = $position->approvals->filter(function (PositionApproval $approval) use ($approversSync) {
            return $approval->modelHasPosition && !$approversSync->deleted->some(fn (Model $model) => $model->is($approval->modelHasPosition->model));
        });

        $position->setRelation('approvals', $newApprovals);
    }

    private function processExternalApprovers(Position $position, Collection $externalApprovers): void
    {
        $externalApproversSync = $this->modelHasPositionRepository->sync(
            position: $position,
            existingModels: $position->externalApprovers,
            models: $externalApprovers,
            role: PositionRoleEnum::EXTERNAL_APPROVER
        );

        $position->setRelation('externalApprovers', $externalApprovers);

        if ($externalApproversSync->deleted->isEmpty()) {
            return;
        }

        $newApprovals = $position->approvals->filter(function (PositionApproval $approval) use ($externalApproversSync) {
            return $approval->modelHasPosition && !$externalApproversSync->deleted->some(fn (Model $model) => $model->is($approval->modelHasPosition->model));
        });

        $position->setRelation('approvals', $newApprovals);
    }

    private function processFiles(Position $position, PositionData $data): void
    {
        if (!$data->hasFiles()) {
            return;
        }

        $files = $this->fileSaver->saveFiles(
            fileable: $position,
            type: FileTypeEnum::POSITION_FILE,
            files: $data->getFilesData(),
            folders: GetModelSubFoldersAction::make()->handle($position)
        );

        $position->setRelation('files', $position->files->push(...$files));
    }
}
