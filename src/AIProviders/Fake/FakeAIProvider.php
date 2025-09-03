<?php

declare(strict_types=1);

namespace AIProviders\Fake;

use Domain\AI\Contracts\AIProviderInterface;
use Domain\AI\Scoring\Enums\ScoreCategoryEnum;
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
        $json = '{"attributes":[{"key":"gender","value":"m"},{"key":"experience","value":"[{\"position\":\"Fullstack web developer\",\"employer\":\"JustIT Pro s.r.o.\",\"from\":\"2019-01-01\",\"to\":\"2021-01-01\",\"description\":\"Development of large HR applications for the biggest corporate in the Czech republic PPF and its subsidiaries in Europe. Scrum, Laravel, JS, Redis, DevOps, Git, Jira, Bitbucket\"},{\"position\":\"Fullstack web developer\",\"employer\":\"DAMI development s.r.o.\",\"from\":\"2021-01-01\",\"to\":\"2022-01-01\",\"description\":\"Development of the Tanix IoT portal connecting hundreds of active sensors and detectors throughout the Czech Republic operating on NB-IoT, Lora, Sigfox, etc. networks. Scrum/Kanban, Yii2, Vue.js, TS, JS, Node.js, RabbitMQ, DevOps, Git, Jira, GitLab\"},{\"position\":\"Fullstack web developer\",\"employer\":\"JustIT Pro s.r.o.\",\"from\":\"2022-01-01\",\"to\":null,\"description\":\"Development of large HR applications for the biggest corporate in the Czech republic PPF and its subsidiaries in Asia and across Europe. Scrum, Laravel, Nuxt, TS, JS, Node.js, Redis, RabbitMQ, DevOps, Git, Jira, Bitbucket\"}]"},{"key":"tags","value":"[\"php\",\"laravel\",\"javascript\",\"typescript\",\"vue.js\",\"nuxt.js\",\"node.js\",\"scrum\",\"kanban\",\"devops\"]"}]}';

        return json_decode($json, true);
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
