<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends BaseFactory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        $action = fake()->randomElement([
            'created',
            'updated',
            'deleted',
            'retrieved',
        ]);

        $data = [];

        if ($action === 'deleted') {
            $data['soft'] = fake()->boolean() ? 1 : 0;
        }

        $subject = fake()->randomElement([
            User::class,
        ]);

        $causer = fake()->randomElement([
            User::class,
        ]);

        return [
            'causer_type' => $causer,
            'causer_id' => $this->isMaking ? null : $causer::factory(),
            'subject_type' => $subject,
            'subject_id' => $this->isMaking ? null : $subject::factory(),
            'action' => $action,
            'data' => $data,
        ];
    }

    public function ofCauser(Model $causer): static
    {
        return $this->state(fn (array $attributes) => [
            'causer_type' => $causer::class,
            'causer_id' => $causer->getKey(),
        ]);
    }

    public function ofSubject(Model $subject): static
    {
        return $this->state(fn (array $attributes) => [
            'subject_type' => $subject::class,
            'subject_id' => $subject->getKey(),
        ]);
    }

    public function ofData(array $data): static
    {
        return $this->state(fn (array $attributes) => [
            'data' => $data,
        ]);
    }

    public function ofMergedData(array $data): static
    {
        return $this->state(fn (array $attributes) => [
            'data' => array_merge($attributes['data'] ?? [], $data),
        ]);
    }
}
