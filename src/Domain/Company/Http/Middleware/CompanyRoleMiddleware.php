<?php

declare(strict_types=1);

namespace Domain\Company\Http\Middleware;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use Domain\Company\Enums\RoleEnum;
use Domain\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

final class CompanyRoleMiddleware
{
    public const IDENTIFIER = 'company-role';

    public function handle(Request $request, \Closure $next, string ...$roles): Response
    {
        /** @var Collection<RoleEnum> $roles */
        $roles = collect($roles)->map(static fn (string $role) => RoleEnum::tryFrom($role))->filter();

        throw_if($roles->isEmpty(), new \InvalidArgumentException('At least one company role needs to be specified.'));

        /** @var User|null $user */
        $user = $request->user();

        throw_if(empty($user), new \Exception('User is not logged in. Cannot check company role.'));

        $hasAnyRole = $roles->some(static fn (RoleEnum $role): bool => $user->company_role === $role);

        if (!$hasAnyRole) {
            throw new HttpException(responseCode: ResponseCodeEnum::UNAUTHORIZED, data: [
                'roles' => $roles->pluck('value')->all(),
            ]);
        }

        return $next($request);
    }

    /**
     * Returns string, which can be used in route definition
     */
    public static function apply(RoleEnum ...$roles): string
    {
        throw_if(empty($roles), new \InvalidArgumentException('At least one company role needs to be specified.'));

        return sprintf('%s:%s', self::IDENTIFIER, collect($roles)->pluck('value')->join(','));
    }
}
