<?php

declare(strict_types=1);

namespace Domain\Application\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Application\Models\Application;
use Domain\Application\Repositories\Input\ApplicationStoreInput;
use Illuminate\Support\Str;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function store(ApplicationStoreInput $input): Application
    {
        $application = new Application();

        $application->uuid = Str::uuid()->toString();
        $application->language = $input->language;
        $application->position_id = $input->position->id;
        $application->candidate_id = null;
        $application->source = $input->source;
        $application->processed = false;
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
}
