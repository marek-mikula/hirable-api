<?php

namespace App\Models\Builders\Traits;

use App\Models\Builders\Builder;

/**
 * @mixin Builder
 */
trait BelongsToUser
{
    /**
     * @param  int|int[]  $id
     */
    public function whereUser(int|array $id): static
    {
        if (is_array($id)) {
            return $this->whereIn('user_id', $id);
        }

        return $this->where('user_id', '=', $id);
    }
}
