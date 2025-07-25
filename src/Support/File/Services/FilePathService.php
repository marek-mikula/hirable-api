<?php

declare(strict_types=1);

namespace Support\File\Services;

use App\Services\Service;
use Illuminate\Database\Eloquent\Model;

class FilePathService extends Service
{
    public function __construct(
        private readonly FileConfigService $fileConfigService,
    ) {
    }

    public function getPathForModel(Model $model, int $step = 500, array $folders = []): string
    {
        $id = (int) $model->getKey();

        $residue = $id % ($step + 1);
        $k = ($id - $residue) / ($step + 1);

        $from = ($k * $step) + 1;
        $to = ($k + 1) * $step;

        return implode(DIRECTORY_SEPARATOR, [
            $this->fileConfigService->getModelFolder($model),
            sprintf('%s-%s', $from, $to),
            (string) $id,
            ...$folders
        ]);
    }
}
