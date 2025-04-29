<?php

declare(strict_types=1);

namespace Domain\Auth\Http\Requests;

use App\Enums\LanguageEnum;
use App\Enums\TimezoneEnum;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\ExcludeIf;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;

class AuthUpdateRequest extends AuthRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();

        $keys = is_array($keys = $this->input('keys', [])) ? $keys : [];

        return [
            // keys array tells us, which attributes
            // do we want to update
            'keys' => [
                'required',
                'array',
                'min:1',
            ],
            'keys.*' => [
                'required',
                'string',
                new In([
                    'firstname',
                    'lastname',
                    'email',
                    'timezone',
                    'password',
                    'notificationTechnicalMail',
                    'notificationTechnicalApp',
                    'notificationTechnicalPush',
                    'notificationMarketingMail',
                    'notificationMarketingApp',
                    'notificationMarketingPush',
                    'notificationApplicationMail',
                    'notificationApplicationApp',
                    'notificationApplicationPush',
                    'language',
                    'prefix',
                    'postfix',
                    'phone',
                ]),
            ],
            'firstname' => [
                new ExcludeIf(!in_array('firstname', $keys)),
                'required',
                'string',
                'max:255',
            ],
            'lastname' => [
                new ExcludeIf(!in_array('lastname', $keys)),
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                new ExcludeIf(!in_array('email', $keys)),
                'required',
                'string',
                'max:255',
                'email',
                (new Unique(User::class, 'email'))->ignoreModel($user),
            ],
            'password' => [
                new ExcludeIf(!in_array('password', $keys)),
                'required',
                'string',
                Password::min(8)
                    ->numbers()
                    ->letters()
                    ->mixedCase()
                    ->symbols(),
            ],
            'oldPassword' => [
                new ExcludeIf(!in_array('password', $keys)),
                'required',
                'string',
                'current_password:api',
            ],
            'passwordConfirm' => [
                new ExcludeIf(!in_array('password', $keys)),
                'required',
                'string',
                'same:password',
            ],
            'timezone' => [
                new ExcludeIf(!in_array('timezone', $keys)),
                'nullable',
                'string',
                new Enum(TimezoneEnum::class),
            ],
            'notificationTechnicalMail' => [
                new ExcludeIf(!in_array('notificationTechnicalMail', $keys)),
                'required',
                'boolean',
            ],
            'notificationTechnicalApp' => [
                new ExcludeIf(!in_array('notificationTechnicalApp', $keys)),
                'required',
                'boolean',
            ],
            'notificationMarketingMail' => [
                new ExcludeIf(!in_array('notificationMarketingMail', $keys)),
                'required',
                'boolean',
            ],
            'notificationMarketingApp' => [
                new ExcludeIf(!in_array('notificationMarketingApp', $keys)),
                'required',
                'boolean',
            ],
            'notificationApplicationMail' => [
                new ExcludeIf(!in_array('notificationApplicationMail', $keys)),
                'required',
                'boolean',
            ],
            'notificationApplicationApp' => [
                new ExcludeIf(!in_array('notificationApplicationApp', $keys)),
                'required',
                'boolean',
            ],
            'language' => [
                new ExcludeIf(!in_array('language', $keys)),
                'required',
                'string',
                new Enum(LanguageEnum::class),
            ],
            'prefix' => [
                new ExcludeIf(!in_array('prefix', $keys)),
                'nullable',
                'string',
                'max:10',
            ],
            'postfix' => [
                new ExcludeIf(!in_array('postfix', $keys)),
                'nullable',
                'string',
                'max:10',
            ],
            'phone' => [
                new ExcludeIf(!in_array('phone', $keys)),
                'nullable',
                'string',
                'max:20',
            ],
        ];
    }

    public function getValues(): array
    {
        $data = [];

        foreach ($this->input('keys', []) as $key) {
            $data[$key] = match ($key) {
                'firstname', 'lastname', 'email', 'password' => (string) $this->input($key),
                'timezone' => $this->filled($key) ? $this->enum($key, TimezoneEnum::class) : null,
                'notificationTechnicalMail',
                'notificationTechnicalApp',
                'notificationMarketingMail',
                'notificationMarketingApp',
                'notificationApplicationMail',
                'notificationApplicationApp' => $this->boolean($key),
                'language' => $this->enum($key, LanguageEnum::class),
                'phone',
                'prefix',
                'postfix' => $this->filled($key) ? (string) $this->input($key) : null,
            };
        }

        return $data;
    }
}
