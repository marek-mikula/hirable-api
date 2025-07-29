<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use App\Rules\Rule;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Policies\PositionProcessStepPolicy;
use Domain\ProcessStep\Models\ProcessStep;
use Illuminate\Contracts\Database\Query\Builder;

class PositionProcessStepStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionProcessStepPolicy::store() */
        return $this->user()->can('store', [PositionProcessStep::class, $this->route('position')]);
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'processStep' => [
                'required',
                'integer',
                Rule::exists(ProcessStep::class, 'id')
                    ->where(function (Builder $query) use ($user): void {
                        $query
                            ->where('company_id', $user->company_id)
                            ->orWhereNull('company_id');
                    }),
            ],
        ];
    }

    public function getProcessStep(): int
    {
        return (int) $this->input('processStep');
    }
}
