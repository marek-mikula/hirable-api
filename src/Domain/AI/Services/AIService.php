<?php

declare(strict_types=1);

namespace Domain\AI\Services;

use App\Services\Service;
use Domain\AI\Contracts\AIProviderInterface;
use Domain\AI\Scoring\Data\ScoreCategoryData;
use Domain\AI\Scoring\Enums\ScoreCategoryEnum;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Support\File\Models\File;

// todo add JSON schema validation to this service
// todo add custom AI exceptions
// todo move JSON decoding into this service
class AIService extends Service
{
    public function __construct(
        private readonly JsonTransformer $jsonTransformer,
        private readonly AIProviderInterface $AIProvider,
    ) {
    }

    /**
     * @return array<string,mixed> attributes as key-value pairs
     */
    public function extractCVData(File $cv): array
    {
        $json = $this->AIProvider->extractCVData($cv);

        $attributes = Arr::get($json, 'attributes', []);

        $attributes = $this->jsonTransformer->transform($attributes);

        return collect($attributes)->pluck('value', 'key')->all();
    }

    /**
     * @param Collection<File> $files
     * @return ScoreCategoryData[]
     */
    public function evaluateCandidate(Position $position, Candidate $candidate, Collection $files): array
    {
        $json = $this->AIProvider->evaluateCandidate($position, $candidate, $files);

        $score = (array) Arr::get($json, 'score', []);

        return array_map(static fn (array $item): ScoreCategoryData => ScoreCategoryData::from([
            'category' => ScoreCategoryEnum::from((string) Arr::get($item, 'category')),
            'score' => (int) Arr::get($item, 'score'),
            'comment' => (string) Arr::get($item, 'comment'),
        ]), $score);
    }

    /**
     * @return array<string,mixed> attributes as key-value pairs
     */
    public function generatePositionFromPrompt(User $user, string $prompt): array
    {
        $json = $this->AIProvider->generatePositionFromPrompt($user, $prompt);

        $attributes = Arr::get($json, 'attributes', []);

        $attributes = $this->jsonTransformer->transform($attributes);

        return collect($attributes)->pluck('value', 'key')->all();
    }

    /**
     * @return array<string,mixed> attributes as key-value pairs
     */
    public function generatePositionFromFile(User $user, UploadedFile $file): array
    {
        $json = $this->AIProvider->generatePositionFromFile($user, $file);

        $attributes = Arr::get($json, 'attributes', []);

        $attributes = $this->jsonTransformer->transform($attributes);

        return collect($attributes)->pluck('value', 'key')->all();
    }
}
