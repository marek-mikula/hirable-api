<?php

declare(strict_types=1);

namespace Domain\Candidate\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Repositories\Input\CandidateStoreInput;
use Domain\Candidate\Repositories\Input\CandidateUpdateInput;

class CandidateRepository implements CandidateRepositoryInterface
{
    public function store(CandidateStoreInput $input): Candidate
    {
        $candidate = new Candidate();

        $candidate->company_id = $input->company->id;
        $candidate->language = $input->language;
        $candidate->firstname = $input->firstname;
        $candidate->lastname = $input->lastname;
        $candidate->email = $input->email;
        $candidate->phone_prefix = $input->phonePrefix;
        $candidate->phone_number = $input->phoneNumber;
        $candidate->linkedin = $input->linkedin;

        throw_if(!$candidate->save(), RepositoryException::stored(Candidate::class));

        $candidate->setRelation('company', $input->company);

        return $candidate;
    }

    public function update(Candidate $candidate, CandidateUpdateInput $input): Candidate
    {
        $candidate->language = $input->language;
        $candidate->gender = $input->gender;
        $candidate->firstname = $input->firstname;
        $candidate->lastname = $input->lastname;
        $candidate->email = $input->email;
        $candidate->phone_prefix = $input->phonePrefix;
        $candidate->phone_number = $input->phoneNumber;
        $candidate->linkedin = $input->linkedin;
        $candidate->instagram = $input->instagram;
        $candidate->github = $input->github;
        $candidate->portfolio = $input->portfolio;
        $candidate->birth_date = $input->birthDate;
        $candidate->experience = $input->experience;

        throw_if(!$candidate->save(), RepositoryException::updated(Candidate::class));

        return $candidate;
    }
}
