<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\Listener;
use Domain\Position\Events\PositionOpenedEvent;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Support\Token\Actions\GenerateTokenAction;

class SetTokensListener extends Listener
{
    public function __construct(
        private readonly PositionRepositoryInterface $positionRepository,
    ) {
    }

    public function handle(PositionOpenedEvent $event): void
    {
        // do not trigger events, because otherwise
        // it would trigger observer infinite times
        Position::withoutEvents(function () use ($event): void {
            $this->positionRepository->setTokens(
                position: $event->position,
                commonToken: GenerateTokenAction::make()->handle(),
                internToken: GenerateTokenAction::make()->handle(),
                referralToken: GenerateTokenAction::make()->handle(),
            );
        });
    }
}
