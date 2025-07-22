<?php

declare(strict_types=1);

namespace Domain\Application\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Application\Models\Application;
use Domain\Application\Repositories\Input\ApplicationStoreInput;
use Domain\Application\Repositories\Input\ApplicationUpdateInput;
use Illuminate\Support\Str;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function store(ApplicationStoreInput $input): Application
    {
        $application = new Application();

        $application->uuid = Str::uuid()->toString();
        $application->position_id = $input->position->id;
        $application->candidate_id = null;
        $application->processed = false;
        $application->language = $input->language;
        $application->source = $input->source;
        $application->firstname = $input->firstname;
        $application->lastname = $input->lastname;
        $application->email = $input->email;
        $application->phone_prefix = $input->phonePrefix;
        $application->phone_number = $input->phoneNumber;
        $application->linkedin = $input->linkedin;

        throw_if(!$application->save(), RepositoryException::stored(Application::class));

        $application->setRelation('position', $input->position);

        return $application;
    }

    public function update(Application $application, ApplicationUpdateInput $input): Application
    {
        $application->candidate_id = $input->candidate?->id;
        $application->language = $input->language;
        $application->gender = $input->gender;
        $application->source = $input->source;
        $application->firstname = $input->firstname;
        $application->lastname = $input->lastname;
        $application->email = $input->email;
        $application->phone_prefix = $input->phonePrefix;
        $application->phone_number = $input->phoneNumber;
        $application->linkedin = $input->linkedin;
        $application->instagram = $input->instagram;
        $application->github = $input->github;
        $application->portfolio = $input->portfolio;
        $application->birth_date = $input->birthDate;
        $application->experience = $input->experience;

        throw_if(!$application->save(), RepositoryException::updated(Application::class));

        $application->setRelation('candidate', $input->candidate);

        return $application;
    }

    public function setProcessed(Application $application): Application
    {
        $application->processed = true;

        throw_if(!$application->save(), RepositoryException::updated(Application::class));

        return $application;
    }

    public function setScore(Application $application, array $score, int $totalScore): Application
    {
        $application->score = $score;
        $application->total_score = $totalScore;

        throw_if(!$application->save(), RepositoryException::updated(Application::class));

        return $application;
    }
}
