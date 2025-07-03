<?php

declare(strict_types=1);

namespace Domain\User\Http\Requests;

use App\Enums\LanguageEnum;
use App\Http\Requests\AuthRequest;
use Domain\User\Models\User;
use Domain\User\Policies\UserPolicy;
use App\Rules\Rule;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see UserPolicy::update() */
        return $this->user()->can('update', $this->route('user'));
    }

    public function rules(): array
    {
        /** @var User $user */
        $user = $this->route('user');

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
                    'password',
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

    public function attributes(): array
    {
        return [
            'firstname' => __('model.common.firstname'),
            'lastname' => __('model.common.lastname'),
            'email' => __('model.common.email'),
            'password' => __('model.user.current_password'),
            'oldPassword' => __('model.user.old_password'),
            'passwordConfirm' => __('model.user.password_confirm'),
            'language' => __('model.common.language'),
            'prefix' => __('model.user.prefix'),
            'postfix' => __('model.user.postfix'),
            'phone' => __('model.common.phone'),
        ];
    }

    public function getValues(): array
    {
        $data = [];

        foreach ($this->array('keys') as $key) {
            $data[$key] = match ($key) {
                'firstname', 'lastname', 'email', 'password' => (string) $this->input($key),
                'language' => $this->enum($key, LanguageEnum::class),
                'phone',
                'prefix',
                'postfix' => $this->filled($key) ? (string) $this->input($key) : null,
            };
        }

        return $data;
    }
}
