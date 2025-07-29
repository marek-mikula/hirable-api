<?php

declare(strict_types=1);

use Domain\AI\Context\Enums\FieldTypeEnum;
use Domain\AI\Context\ValueSerializers\ClassifierSerializer;
use Domain\AI\Context\ValueSerializers\CustomSerializer;
use Domain\AI\Context\ValueSerializers\IntegerSerializer;
use Domain\AI\Context\ValueSerializers\LanguageRequirementValueSerializer;
use Domain\AI\Context\ValueSerializers\StringSerializer;
use Domain\AI\Enums\AIServiceEnum;
use Domain\AI\Scoring\Enums\ScoreCategoryEnum;
use Domain\AI\Services\FakeAIService;
use Domain\Position\Enums\PositionFieldEnum;
use Domain\Position\Models\Position;
use Services\OpenAI\Services\OpenAIService;
use Support\Classifier\Enums\ClassifierTypeEnum;

return [

    /*
    |--------------------------------------------------------------------------
    | Selected AI service
    |--------------------------------------------------------------------------
    |
    | AI service which is used for AI functions. Service name should be
    | registered in the 'services' array below.
    |
    */

    'service' => env('AI_SERVICE', AIServiceEnum::OPENAI->value),

    /*
    |--------------------------------------------------------------------------
    | AI services
    |--------------------------------------------------------------------------
    |
    | List of AI services. Each service should implement AI service
    | contract.
    |
    */

    'services' => [
        AIServiceEnum::OPENAI->value => OpenAIService::class,
        AIServiceEnum::FAKE->value => FakeAIService::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Model context
    |--------------------------------------------------------------------------
    |
    | Models context serialization definitions.
    |
    */

    'context' => [
        'serializers' => [
            FieldTypeEnum::STRING->value => StringSerializer::class,
            FieldTypeEnum::INTEGER->value => IntegerSerializer::class,
            FieldTypeEnum::CLASSIFIER->value => ClassifierSerializer::class,
            FieldTypeEnum::CUSTOM->value => CustomSerializer::class,
        ],
        'models' => [
            Position::class => [
                PositionFieldEnum::NAME->value => [
                    'type' => FieldTypeEnum::STRING->value,
                    'attribute' => 'name', /** @see Position::$name */
                    'label' => 'Position name',
                ],
                PositionFieldEnum::DESCRIPTION->value => [
                    'type' => FieldTypeEnum::STRING->value,
                    'attribute' => 'description', /** @see Position::$description */
                    'label' => 'Position description',
                ],
                PositionFieldEnum::MIN_EDUCATION_LEVEL->value => [
                    'type' => FieldTypeEnum::CLASSIFIER->value,
                    'attribute' => 'min_education_level', /** @see Position::$min_education_level */
                    'classifier' => ClassifierTypeEnum::EDUCATION_LEVEL->value,
                    'label' => 'Required education level',
                ],
                PositionFieldEnum::EDUCATION_FIELD->value => [
                    'type' => FieldTypeEnum::STRING->value,
                    'attribute' => 'education_field', /** @see Position::$education_field */
                    'label' => 'Required education field',
                ],
                PositionFieldEnum::SENIORITY->value => [
                    'type' => FieldTypeEnum::CLASSIFIER->value,
                    'attribute' => 'seniority', /** @see Position::$seniority */
                    'is_array' => true,
                    'classifier' => ClassifierTypeEnum::SENIORITY->value,
                    'label' => 'Required seniority for position',
                ],
                PositionFieldEnum::EXPERIENCE->value => [
                    'type' => FieldTypeEnum::INTEGER->value,
                    'attribute' => 'experience', /** @see Position::$experience */
                    'label' => 'Required number of years of experience',
                ],
                PositionFieldEnum::HARD_SKILLS->value => [
                    'type' => FieldTypeEnum::STRING->value,
                    'attribute' => 'hard_skills', /** @see Position::$hard_skills */
                    'label' => 'Required other hard skills',
                ],
                PositionFieldEnum::ORGANISATION_SKILLS->value => [
                    'type' => FieldTypeEnum::INTEGER->value,
                    'attribute' => 'organisation_skills', /** @see Position::$organisation_skills */
                    'label' => 'Required organisation skills (scale 0-100; 0 = not required, 100 = very important)',
                ],
                PositionFieldEnum::TEAM__SKILLS->value => [
                    'type' => FieldTypeEnum::INTEGER->value,
                    'attribute' => 'team_skills', /** @see Position::$team_skills */
                    'label' => 'Required team skills (scale 0-100; 0 = not required, 100 = very important)',
                ],
                PositionFieldEnum::TIME__MANAGEMENT->value => [
                    'type' => FieldTypeEnum::INTEGER->value,
                    'attribute' => 'time_management', /** @see Position::$time_management */
                    'label' => 'Required time management skills (scale 0-100; 0 = not required, 100 = very important)',
                ],
                PositionFieldEnum::COMMUNICATION__SKILLS->value => [
                    'type' => FieldTypeEnum::INTEGER->value,
                    'attribute' => 'communication_skills', /** @see Position::$communication_skills */
                    'label' => 'Required communication skills (scale 0-100; 0 = not required, 100 = very important)',
                ],
                PositionFieldEnum::LEADERSHIP->value => [
                    'type' => FieldTypeEnum::INTEGER->value,
                    'attribute' => 'leadership', /** @see Position::$leadership */
                    'label' => 'Required leadership skills (scale 0-100; 0 = not required, 100 = very important)',
                ],
                PositionFieldEnum::LANGUAGE__REQUIREMENTS->value => [
                    'type' => FieldTypeEnum::CUSTOM->value,
                    'attribute' => 'language_requirements', /** @see Position::$language_requirements */
                    'is_array' => true,
                    'label' => 'Language requirements',
                    'serializer' => LanguageRequirementValueSerializer::class,
                ],
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Scoring
    |--------------------------------------------------------------------------
    |
    | Score categories are used in candidate scoring to score his fit
    | on position by several categories described in the list below.
    |
    | Score files defines what file extensions are sent to the AI for
    | evaluation.
    |
    | Base weight defined the base weight for each score category. If
    | user sets 0 to each weight, we would divide by 0, so we add this
    | base weight to each category weight to prevent that.
    |
    */

    'score' => [
        'files' => [
            'pdf',
            'docx',
            'xlsx',
        ],
        'category_descriptions' => [
            ScoreCategoryEnum::HARD_SKILLS->value => 'In this category, your goal is to assess the specific, measurable, and professional skills necessary to perform the job. Carefully compare the required knowledge, such as specific methods, techniques, legislative knowledge, or equipment operation, with what the candidate lists. Also, look for experience with specific software, tools, or platforms relevant to the given field (e.g., Google Analytics in marketing, AutoCAD in engineering, accounting systems in finance). Evaluate the level of proficiency if it is apparent from the context. In your evaluation, clearly identify which key professional skills the candidate meets, which they are missing, and where they might offer a relevant alternative.',
            ScoreCategoryEnum::SOFT_SKILLS->value => 'Here, your task is to assess the candidate\'s interpersonal skills and work habits. As soft skills are rarely listed explicitly, you must infer them from the descriptions of work experience, activities, and phrasing. Analyze the descriptions of duties and look for evidence of the required competencies: for instance, "leading a team" implies leadership, "collaborating with other departments" suggests teamwork and communication, and "designing a new solution" indicates proactivity and problem-solving skills. In your evaluation, do not just state the skill generally; instead, support it with specific evidence from the candidate\'s documents.',
            ScoreCategoryEnum::LANGUAGE_SKILLS->value => 'In this category, verify if the candidate\'s language abilities meet the specific requirements of the position. Focus on a direct comparison of the required languages and their proficiency levels. It is crucial that you normalize different formats of proficiency levels – for example, understand that "fluent" corresponds roughly to the C1 level, while "communicative" is closer to B2. In your evaluation, clearly state whether the candidate meets or exceeds the required level, or if their knowledge is insufficient. If a language is listed only as a "nice-to-have," consider their knowledge of it as a bonus.',
            ScoreCategoryEnum::EDUCATION->value => 'Your task is to assess how the candidate\'s formal education, certifications, and licenses align with the requirements. Compare the required level of education and field of study with what the candidate lists, and evaluate whether the field is directly relevant, related, or different. Simultaneously, actively look for specific professional certifications, licenses, or credentials relevant to the given field (e.g., PMP certification for project managers, auditor exams, professional licenses). Also, consider the overall relevance of the education for the given role, as practical experience may outweigh a formal degree in many positions.',
            ScoreCategoryEnum::EXPERIENCE->value => 'In this key category, comprehensively evaluate the relevance, depth, and duration of the candidate\'s prior work experience. Calculate the total length of relevant experience and compare it with the position\'s seniority requirement. Do not just analyze job titles, but primarily focus on the detailed descriptions of responsibilities and duties, comparing them with the key tasks listed in the job description. Determine if the candidate has experience in the required type of industry or market (e.g., manufacturing, healthcare, non-profit, B2C sales). Also, look for quantifiable achievements, such as sales targets met, projects successfully completed, or process improvements implemented, as they are strong evidence of competence. The goal is to synthesize all these factors into one overall assessment.',
        ],
        'base_weight' => 100,
    ],

];
