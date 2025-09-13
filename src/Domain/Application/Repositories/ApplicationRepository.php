<?php

declare(strict_types=1);

namespace Domain\Application\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Application\Models\Application;
use Domain\Application\Models\Builders\ApplicationBuilder;
use Domain\Application\Repositories\Input\ApplicationStoreInput;
use Domain\Position\Models\Position;
use Illuminate\Support\Str;

final readonly class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function store(ApplicationStoreInput $input): Application
    {
        $application = new Application();

        $application->uuid = Str::uuid()->toString();
        $application->position_id = $input->position->id;
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

    public function existsDuplicateOnPosition(Position $position, string $email, string $phonePrefix, string $phoneNumber): bool
    {
        return Application::query()
            ->wherePosition($position->id)
            ->where(function (ApplicationBuilder $query) use ($email, $phonePrefix, $phoneNumber): void {
                $query
                    ->where('email', $email)
                    ->orWhere(function (ApplicationBuilder $query) use ($phonePrefix, $phoneNumber): void {
                        $query
                            ->where('phone_prefix', $phonePrefix)
                            ->where('phone_number', $phoneNumber);
                    });
            })
            ->exists();
    }
}
