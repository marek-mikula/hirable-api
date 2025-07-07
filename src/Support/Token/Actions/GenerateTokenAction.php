<?php

declare(strict_types=1);

namespace Support\Token\Actions;

use App\Actions\Action;
use Illuminate\Support\Str;

class GenerateTokenAction extends Action
{
    public function handle(): string
    {
        return hash_hmac('sha256', Str::random(40), (string) config('app.key'));
    }
}
