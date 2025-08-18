<?php

declare(strict_types=1);

namespace Domain\AI\Services;

use Carbon\Carbon;
use Domain\AI\Contracts\AIServiceInterface;
use Domain\AI\Data\CVData;
use Domain\AI\Scoring\Data\ScoreCategoryData;
use Domain\AI\Scoring\Enums\ScoreCategoryEnum;
use Domain\Candidate\Enums\GenderEnum;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Support\File\Models\File;

class FakeAIService implements AIServiceInterface
{
    public function extractCVData(File $cv): CVData
    {
        return CVData::from([
            'gender' => fake()->randomElement(GenderEnum::cases()),
            'birthDate' => Carbon::createFromFormat('Y-m-d', fake()->date),
            'instagram' => null,
            'github' => null,
            'portfolio' => null,
            'experience' => [],
            'tags' => fake()->words(fake()->numberBetween(1, 5)),
        ]);
    }

    public function evaluateCandidate(Position $position, Candidate $candidate, Collection $files): array
    {
        $score = [];

        foreach (ScoreCategoryEnum::cases() as $scoreCategory) {
            $score[] = ScoreCategoryData::from([
                'category' => $scoreCategory,
                'score' => fake()->numberBetween(0, 100),
                'comment' => fake()->sentence,
            ]);
        }

        return $score;
    }

    public function generatePositionFromPrompt(User $user, string $prompt): array
    {
        $result = (object) [
            'outputText' => '{"attributes":[{"key":"name","value":"Koordinátor interní komunikace a podpory týmové spolupráce"},{"key":"field","value":"hr"},{"key":"workloads","value":["full_time"]},{"key":"employmentRelationships","value":["contract"]},{"key":"employmentForms","value":["on_site"]},{"key":"jobSeatsNum","value":1},{"key":"description","value":"Koordinace interních informačních toků, příprava a distribuce interních newsletterů, organizace porad a firemních setkání (prezenčně i online), tvorba prezentačních materiálů pro vedení, podpora při interních školeních, zavádění nových komunikačních nástrojů a zlepšování firemní kultury prostřednictvím workshopů a zpětné vazby od zaměstnanců."},{"key":"salaryFrom","value":32000},{"key":"salaryTo","value":40000},{"key":"salaryType","value":"gross"},{"key":"salaryFrequency","value":"monthly"},{"key":"salaryCurrency","value":"CZK"},{"key":"benefits","value":["flexible_hours","meal_contribution","sports_card","training_education"]},{"key":"minEducationLevel","value":"secondary_certificate"},{"key":"educationField","value":"komunikace, marketing, management nebo příbuzné obory"},{"key":"seniority","value":["medior"]},{"key":"experience","value":0},{"key":"hardSkills","value":"Pokročilá práce s MS Office (Word, Excel, PowerPoint), zkušenosti s nástroji pro online spolupráci (Teams, Slack, Trello, Asana), základy grafické úpravy (Canva, Adobe Illustrator nebo Photoshop), orientace v platformách pro hromadnou komunikaci a newslettery (Mailchimp, Ecomail), schopnost pracovat s daty z interních průzkumů a připravovat jednoduché reporty."},{"key":"organisationSkills","value":80},{"key":"teamSkills","value":80},{"key":"timeManagement","value":70},{"key":"communicationSkills","value":90},{"key":"leadership","value":50},{"key":"languageRequirements","value":["czech-c2","english-b2"]}]}'
        ];

        try {
            $json = json_decode((string) $result->outputText, true, flags: JSON_THROW_ON_ERROR);
        } catch (\Exception) {
            throw new \Exception('Could not parse JSON output.');
        }

        $attributes = Arr::get($json, 'attributes', []);

        return collect($attributes)
            ->mapWithKeys(function (array $attribute): array {
                $key = Arr::get($attribute, 'key');
                $value = Arr::get($attribute, 'value');
                return [$key => $value];
            })
            ->filter()
            ->all();
    }

    public function generatePositionFromFile(User $user, UploadedFile $file): array
    {
        $result = (object) [
            'outputText' => '{"attributes":[{"key":"name","value":"Koordinátor interní komunikace a podpory týmové spolupráce"},{"key":"field","value":"hr"},{"key":"workloads","value":["full_time"]},{"key":"employmentRelationships","value":["contract"]},{"key":"employmentForms","value":["on_site"]},{"key":"jobSeatsNum","value":1},{"key":"description","value":"Koordinace interních informačních toků, příprava a distribuce interních newsletterů, organizace porad a firemních setkání (prezenčně i online), tvorba prezentačních materiálů pro vedení, podpora při interních školeních, zavádění nových komunikačních nástrojů a zlepšování firemní kultury prostřednictvím workshopů a zpětné vazby od zaměstnanců."},{"key":"salaryFrom","value":32000},{"key":"salaryTo","value":40000},{"key":"salaryType","value":"gross"},{"key":"salaryFrequency","value":"monthly"},{"key":"salaryCurrency","value":"CZK"},{"key":"benefits","value":["flexible_hours","meal_contribution","sports_card","training_education"]},{"key":"minEducationLevel","value":"secondary_certificate"},{"key":"educationField","value":"komunikace, marketing, management nebo příbuzné obory"},{"key":"seniority","value":["medior"]},{"key":"experience","value":0},{"key":"hardSkills","value":"Pokročilá práce s MS Office (Word, Excel, PowerPoint), zkušenosti s nástroji pro online spolupráci (Teams, Slack, Trello, Asana), základy grafické úpravy (Canva, Adobe Illustrator nebo Photoshop), orientace v platformách pro hromadnou komunikaci a newslettery (Mailchimp, Ecomail), schopnost pracovat s daty z interních průzkumů a připravovat jednoduché reporty."},{"key":"organisationSkills","value":80},{"key":"teamSkills","value":80},{"key":"timeManagement","value":70},{"key":"communicationSkills","value":90},{"key":"leadership","value":50},{"key":"languageRequirements","value":["czech-c2","english-b2"]}]}'
        ];

        try {
            $json = json_decode((string) $result->outputText, true, flags: JSON_THROW_ON_ERROR);
        } catch (\Exception) {
            throw new \Exception('Could not parse JSON output.');
        }

        $attributes = Arr::get($json, 'attributes', []);

        return collect($attributes)
            ->mapWithKeys(function (array $attribute): array {
                $key = Arr::get($attribute, 'key');
                $value = Arr::get($attribute, 'value');
                return [$key => $value];
            })
            ->filter()
            ->all();
    }
}
