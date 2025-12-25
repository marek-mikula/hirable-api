<?php

declare(strict_types=1);

namespace Domain\Candidate\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Candidate\Models\Builders\CandidateBuilder;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Repositories\Input\CandidateStoreInput;
use Domain\Candidate\Repositories\Input\CandidateUpdateInput;
use Domain\Company\Models\Company;

final readonly class CandidateRepository implements CandidateRepositoryInterface
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
        $candidate->gender = $input->gender;
        $candidate->linkedin = $input->linkedin;
        $candidate->instagram = $input->instagram;
        $candidate->github = $input->github;
        $candidate->portfolio = $input->portfolio;
        $candidate->birth_date = $input->birthDate;
        $candidate->experience = $input->experience;
        $candidate->tags = $input->tags;

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
        $candidate->tags = $input->tags;

        throw_if(!$candidate->save(), RepositoryException::updated(Candidate::class));

        return $candidate;
    }

    public function findDuplicateInCompany(
        Company $company,
        string $email,
        string $phonePrefix,
        string $phoneNumber
    ): ?Candidate {
        /** @var Candidate|null $candidate */
        $candidate = Candidate::query()
            ->whereCompany($company->id)
            ->where(function (CandidateBuilder $query) use ($email, $phonePrefix, $phoneNumber): void {
                $query
                    ->where('email', $email)
                    ->orWhere(function (CandidateBuilder $query) use ($phonePrefix, $phoneNumber): void {
                        $query
                            ->where('phone_prefix', $phonePrefix)
                            ->where('phone_number', $phoneNumber);
                    });
            })
            ->first();

        return $candidate;
    }
}
