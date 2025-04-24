<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Action;

class GetModelSubFoldersAction extends Action
{
    /**
     * @return string[]
     */
    public function handle(Model $model, int $step = 500, array $folders = []): array
    {
        $id = (int) $model->getAttribute('id');

        $residue = $id % ($step + 1);
        $k = ($id - $residue) / ($step + 1);

        $from = ($k * $step) + 1;
        $to = ($k + 1) * $step;

        return [sprintf('%s-%s', $from, $to), (string) $id, ...$folders];
    }
}
