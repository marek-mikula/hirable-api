<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Support\File\Repositories\FileRepositoryInterface;

class PositionDeleteUseCase extends UseCase
{
    public function __construct(
        private readonly PositionRepositoryInterface $positionRepository,
        private readonly FileRepositoryInterface $fileRepository,
    ) {
    }

    public function handle(User $user, Position $position): void
    {
        $position->loadMissing([
            'files',
        ]);

        DB::transaction(function () use ($position): void {
            if ($position->files->isNotEmpty()) {
                foreach ($position->files as $file) {
                    $this->fileRepository->delete($file);
                }
            }

            $this->positionRepository->delete($position);
        }, attempts: 5);
    }
}
