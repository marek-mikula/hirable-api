<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Models\Position;

class PositionApprovalCancelRequest extends AuthRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        /** @var Position $position */
        $position = $this->route('position');

        return $position->user_id === $user->id; // user must be the owner
    }

    public function rules(): array
    {
        return [];
    }
}
