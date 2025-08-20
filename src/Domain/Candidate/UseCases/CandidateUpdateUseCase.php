<?php

declare(strict_types=1);

namespace Domain\Candidate\UseCases;

use App\UseCases\UseCase;
use Domain\Candidate\Http\Request\Data\CandidateUpdateData;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Repositories\CandidateRepositoryInterface;
use Domain\Candidate\Repositories\Input\CandidateUpdateInput;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class CandidateUpdateUseCase extends UseCase
{
    public function __construct(
        private readonly CandidateRepositoryInterface $candidateRepository,
    ) {
    }

    public function handle(User $user, Candidate $candidate, CandidateUpdateData $data): Candidate
    {
        $input = new CandidateUpdateInput(
            language: $data->hasKey('language') ? $data->language : $candidate->language,
            gender: $data->hasKey('gender') ? $data->gender : $candidate->gender,
            firstname: $data->hasKey('firstname') ? $data->firstname : $candidate->firstname,
            lastname: $data->hasKey('lastname') ? $data->lastname : $candidate->lastname,
            email: $data->hasKey('email') ? $data->email : $candidate->email,
            phonePrefix: $data->hasKey('phone') ? $data->phonePrefix : $candidate->phone_prefix,
            phoneNumber: $data->hasKey('phone') ? $data->phoneNumber : $candidate->phone_number,
            linkedin: $data->hasKey('linkedin') ? $data->linkedin : $candidate->linkedin,
            instagram: $data->hasKey('instagram') ? $data->instagram : $candidate->instagram,
            github: $data->hasKey('github') ? $data->github : $candidate->github,
            portfolio: $data->hasKey('portfolio') ? $data->portfolio : $candidate->portfolio,
            birthDate: $data->hasKey('birthDate') ? $data->birthDate : $candidate->birth_date,
            experience: $candidate->experience,
            tags: $data->hasKey('tags') ? $data->tags : $candidate->tags,
        );

        return DB::transaction(function () use (
            $candidate,
            $input,
        ): Candidate {
            return $this->candidateRepository->update($candidate, $input);
        }, attempts: 5);
    }
}
