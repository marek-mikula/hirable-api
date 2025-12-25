<?php

declare(strict_types=1);

namespace AIProviders\Fake;

use App\Enums\LanguageEnum;
use Domain\AI\Contracts\AIProviderInterface;
use Domain\AI\Scoring\Enums\ScoreCategoryEnum;
use Domain\Candidate\Enums\CandidateFieldEnum;
use Domain\Candidate\Enums\GenderEnum;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Support\File\Models\File;

class FakeAIProvider implements AIProviderInterface
{
    public function extractCVData(File $cv): array
    {
        $gender = fake()->randomElement(['male', 'female']);

        $experiences = [];
        for ($x = 0; $x < fake()->numberBetween(1, 4); $x++) {
            $experiences[] = [
                'position' => fake()->jobTitle,
                'employer' => fake()->company,
                'description' => fake()->text,
                'from' => now()->startOfYear()->addYears($x)->format('Y-m-d'),
                'to' => now()->endOfYear()->addYears($x)->format('Y-m-d'),
            ];
        }

        return [
            'attributes' => [
                [
                    'key' => CandidateFieldEnum::FIRSTNAME->value,
                    'value' => fake()->firstName($gender),
                ],
                [
                    'key' => CandidateFieldEnum::LASTNAME->value,
                    'value' => fake()->lastName($gender),
                ],
                [
                    'key' => CandidateFieldEnum::GENDER->value,
                    'value' => match ($gender) {
                        'male' => GenderEnum::MALE->value,
                        'female' => GenderEnum::FEMALE->value,
                    },
                ],
                [
                    'key' => CandidateFieldEnum::LANGUAGE->value,
                    'value' => LanguageEnum::CS->value,
                ],
                [
                    'key' => CandidateFieldEnum::EMAIL->value,
                    'value' => fake()->email,
                ],
                [
                    'key' => CandidateFieldEnum::PHONE_PREFIX->value,
                    'value' => '+420',
                ],
                [
                    'key' => CandidateFieldEnum::PHONE_NUMBER->value,
                    'value' => fake()->numerify('#########'),
                ],
                [
                    'key' => CandidateFieldEnum::LINKEDIN->value,
                    'value' => null,
                ],
                [
                    'key' => CandidateFieldEnum::INSTAGRAM->value,
                    'value' => null,
                ],
                [
                    'key' => CandidateFieldEnum::GITHUB->value,
                    'value' => null,
                ],
                [
                    'key' => CandidateFieldEnum::PORTFOLIO->value,
                    'value' => null,
                ],
                [
                    'key' => CandidateFieldEnum::BIRTH_DATE->value,
                    'value' => now()
                        ->subYears(fake()->numberBetween(10, 50))
                        ->setMonth(fake()->numberBetween(1, 12))
                        ->setDay(fake()->numberBetween(1, 28))
                        ->format('Y-m-d'),
                ],
                [
                    'key' => CandidateFieldEnum::EXPERIENCE->value,
                    'value' => json_encode($experiences),
                ],
                [
                    'key' => CandidateFieldEnum::TAGS->value,
                    'value' => json_encode(fake()->words(fake()->numberBetween(1, 5))),
                ],
            ],
        ];
    }

    public function evaluateCandidate(Position $position, Candidate $candidate, Collection $files): array
    {
        $data = [
            'score' => [],
        ];

        foreach (ScoreCategoryEnum::cases() as $category) {
            $data['score'][] = [
                'category' => $category->value,
                'score' => fake()->numberBetween(0, 100),
                'comment' => fake()->sentence,
            ];
        }

        return $data;
    }

    public function generatePositionFromPrompt(User $user, string $prompt): array
    {
        $json = '{"attributes":[{"key":"name","value":"Cloud Solutions Engineer"},{"key":"field","value":"it_admin"},{"key":"workloads","value":"[\"full_time\"]"},{"key":"employmentRelationships","value":"[\"contract\"]"},{"key":"employmentForms","value":"[\"on_site\",\"remote\"]"},{"key":"jobSeatsNum","value":2},{"key":"description","value":"Odpovědnosti:\n• Správa a optimalizace cloudových prostředí (AWS, Azure)\n• Implementace CI/CD pipeline\n• Spolupráce s DevOps týmem a vývojáři\n• Monitorování a řešení incidentů\n\nNáplň práce:\n• Deployment aplikací do cloudu\n• Automatizace procesů pomocí Terraform a Ansible\n• Bezpečnostní audit cloudových služeb\n• Reporting a dokumentace\n\nPopis týmu:\nNáš tým tvoří 8 specialistů – cloud inženýři, DevOps a architekti. Pracujeme v agilním prostředí a preferujeme otevřenou komunikaci.\n\nPopis prostředí:\nModerní kanceláře v centru Prahy, hybridní režim, ergonomické pracovní stanice a možnost práce z domova."},{"key":"salaryFrom","value":95000},{"key":"salaryFrequency","value":"monthly"},{"key":"salaryCurrency","value":"CZK"},{"key":"salaryVar","value":"Roční bonus až 15 %"},{"key":"benefits","value":"[\"more_vacation\",\"sports_card\",\"company_events\",\"training_education\",\"company_phone_laptop\",\"meal_contribution\"]"},{"key":"minEducationLevel","value":"higher"},{"key":"educationField","value":"Informatika / Elektrotechnika"},{"key":"seniority","value":"[\"medior\",\"senior\"]"},{"key":"experience","value":3},{"key":"hardSkills","value":"- AWS\n- Azure\n- Docker\n- Kubernetes\n- Terraform\n- Ansible\n- AWS Certified Solutions Architect (výhodou)"},{"key":"languageRequirements","value":"[{\"language\":\"english\",\"level\":\"b2\"},{\"language\":\"czech\",\"level\":\"c1\"}]"},{"key":"tags","value":"[\"cloud\",\"aws\",\"azure\",\"devops\",\"terraform\",\"ansible\",\"docker\",\"kubernetes\",\"ci/cd\",\"automation\"]"},{"key":"organisationSkills","value":80},{"key":"teamSkills","value":90},{"key":"timeManagement","value":80},{"key":"communicationSkills","value":90}]}';

        return json_decode($json, true);
    }

    public function generatePositionFromFile(User $user, UploadedFile $file): array
    {
        $json = '{"attributes":[{"key":"name","value":"Cloud Solutions Engineer"},{"key":"field","value":"it_admin"},{"key":"workloads","value":"[\"full_time\"]"},{"key":"employmentRelationships","value":"[\"contract\"]"},{"key":"employmentForms","value":"[\"on_site\",\"remote\"]"},{"key":"jobSeatsNum","value":2},{"key":"description","value":"Odpovědnosti:\n• Správa a optimalizace cloudových prostředí (AWS, Azure)\n• Implementace CI/CD pipeline\n• Spolupráce s DevOps týmem a vývojáři\n• Monitorování a řešení incidentů\n\nNáplň práce:\n• Deployment aplikací do cloudu\n• Automatizace procesů pomocí Terraform a Ansible\n• Bezpečnostní audit cloudových služeb\n• Reporting a dokumentace\n\nPopis týmu:\nNáš tým tvoří 8 specialistů – cloud inženýři, DevOps a architekti. Pracujeme v agilním prostředí a preferujeme otevřenou komunikaci.\n\nPopis prostředí:\nModerní kanceláře v centru Prahy, hybridní režim, ergonomické pracovní stanice a možnost práce z domova."},{"key":"salaryFrom","value":95000},{"key":"salaryFrequency","value":"monthly"},{"key":"salaryCurrency","value":"CZK"},{"key":"salaryVar","value":"Roční bonus až 15 %"},{"key":"benefits","value":"[\"more_vacation\",\"sports_card\",\"company_events\",\"training_education\",\"company_phone_laptop\",\"meal_contribution\"]"},{"key":"minEducationLevel","value":"higher"},{"key":"educationField","value":"Informatika / Elektrotechnika"},{"key":"seniority","value":"[\"medior\",\"senior\"]"},{"key":"experience","value":3},{"key":"hardSkills","value":"- AWS\n- Azure\n- Docker\n- Kubernetes\n- Terraform\n- Ansible\n- AWS Certified Solutions Architect (výhodou)"},{"key":"languageRequirements","value":"[{\"language\":\"english\",\"level\":\"b2\"},{\"language\":\"czech\",\"level\":\"c1\"}]"},{"key":"tags","value":"[\"cloud\",\"aws\",\"azure\",\"devops\",\"terraform\",\"ansible\",\"docker\",\"kubernetes\",\"ci/cd\",\"automation\"]"},{"key":"organisationSkills","value":80},{"key":"teamSkills","value":90},{"key":"timeManagement","value":80},{"key":"communicationSkills","value":90}]}';

        return json_decode($json, true);
    }
}
