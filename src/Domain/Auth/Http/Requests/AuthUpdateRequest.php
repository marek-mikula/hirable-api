<?php

declare(strict_types=1);

namespace Domain\Auth\Http\Requests;

use App\Enums\LanguageEnum;
use App\Enums\TimezoneEnum;
use App\Http\Requests\AuthRequest;
use Domain\User\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
            ],
            'keys.*' => [
                'required',
                'string',
                Rule::in([
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
                ])
            ],
            'firstname' => [
                Rule::excludeIf(!in_array('firstname', $keys)),
                'required',
                'string',
                'max:255',
            ],
            'lastname' => [
                Rule::excludeIf(!in_array('lastname', $keys)),
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                Rule::excludeIf(!in_array('email', $keys)),
                'required',
                'string',
                'max:255',
                'email',
                Rule::unique(User::class, 'email')->ignoreModel($user),
            ],
            'password' => [
                Rule::excludeIf(!in_array('password', $keys)),
                'required',
                'string',
                Password::min(8)
                    ->numbers()
                    ->letters()
                    ->mixedCase()
                    ->symbols(),
            ],
            'oldPassword' => [
                Rule::excludeIf(!in_array('password', $keys)),
                'required',
                'string',
                'current_password:api',
            ],
            'passwordConfirm' => [
                Rule::excludeIf(!in_array('password', $keys)),
                'required',
                'string',
                'same:password',
            ],
            'timezone' => [
                Rule::excludeIf(!in_array('timezone', $keys)),
                'nullable',
                'string',
                Rule::enum(TimezoneEnum::class),
            ],
            'notificationTechnicalMail' => [
                Rule::excludeIf(!in_array('notificationTechnicalMail', $keys)),
                'required',
                'boolean',
            ],
            'notificationTechnicalApp' => [
                Rule::excludeIf(!in_array('notificationTechnicalApp', $keys)),
                'required',
                'boolean',
            ],
            'notificationMarketingMail' => [
                Rule::excludeIf(!in_array('notificationMarketingMail', $keys)),
                'required',
                'boolean',
            ],
            'notificationMarketingApp' => [
                Rule::excludeIf(!in_array('notificationMarketingApp', $keys)),
                'required',
                'boolean',
            ],
            'notificationApplicationMail' => [
                Rule::excludeIf(!in_array('notificationApplicationMail', $keys)),
                'required',
                'boolean',
            ],
            'notificationApplicationApp' => [
                Rule::excludeIf(!in_array('notificationApplicationApp', $keys)),
                'required',
                'boolean',
            ],
            'language' => [
                Rule::excludeIf(!in_array('language', $keys)),
                'required',
                'string',
                Rule::enum(LanguageEnum::class),
            ],
            'prefix' => [
                Rule::excludeIf(!in_array('prefix', $keys)),
                'nullable',
                'string',
                'max:10',
            ],
            'postfix' => [
                Rule::excludeIf(!in_array('postfix', $keys)),
                'nullable',
                'string',
                'max:10',
            ],
            'phone' => [
                Rule::excludeIf(!in_array('phone', $keys)),
                'nullable',
                'string',
                'max:20',
            ],
        ];
    }

    public function getValues(): array
    {
        $data = [];

        foreach ($this->array('keys') as $key) {
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
