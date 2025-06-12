<?php

declare(strict_types=1);

namespace Support\File\Policies;

use Domain\Position\Models\Position;
use Domain\Position\Policies\PositionPolicy;
use Domain\User\Models\User;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;

class FilePolicy
{
    public function download(User $user, File $file): bool
    {
        return $this->show($user, $file);
    }

    public function show(User $user, File $file): bool
    {
        return match ($file->type) {
            FileTypeEnum::POSITION_FILE => $this->showPositionFile($user, $file),
            default => false
        };
    }

    public function delete(User $user, File $file): bool
    {
        return match ($file->type) {
            FileTypeEnum::POSITION_FILE => $this->deletePositionFile($user, $file),
            default => false
        };
    }

    private function showPositionFile(User $user, File $file): bool
    {
        /** @var Position $position */
        $position = $file->loadMissing('fileable')->fileable;

        /** @see PositionPolicy::show() */
        return $user->can('show', $position);
    }

    private function deletePositionFile(User $user, File $file): bool
    {
        /** @var Position $position */
        $position = $file->loadMissing('fileable')->fileable;

        /** @see PositionPolicy::update() */
        return $user->can('update', $position);
    }
}
