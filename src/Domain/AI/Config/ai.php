<?php

declare(strict_types=1);

use Domain\AI\Context\Mappers\PositionMapper;
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
        'mappers' => [
            Position::class => PositionMapper::class,
        ],
        'models' => [
            Position::class => [
                PositionFieldEnum::NAME->value => [
                    'label' => 'Name',
                    'constraint' => 'string, max. 255 chars',
                ],
                PositionFieldEnum::DEPARTMENT->value => [
                    'label' => 'Department',
                    'constraint' => 'string, max. 255 chars',
                ],
                PositionFieldEnum::FIELD->value => [
                    'label' => 'Field',
                    'classifier' => ClassifierTypeEnum::FIELD->value,
                    'constraint' => 'string, classifier key',
                ],
                PositionFieldEnum::WORKLOADS->value => [
                    'label' => 'Workload',
                    'classifier' => ClassifierTypeEnum::WORKLOAD->value,
                    'description' => 'If employee works full-time, part-time or else.',
                    'constraint' => 'array, classifier keys',
                ],
                PositionFieldEnum::EMPLOYMENT_RELATIONSHIPS->value => [
                    'label' => 'Employment relationship',
                    'classifier' => ClassifierTypeEnum::EMPLOYMENT_RELATIONSHIP->value,
                    'description' => 'If the relationship is contract, internship or else.',
                    'constraint' => 'array, classifier keys',
                ],
                PositionFieldEnum::EMPLOYMENT_FORMS->value => [
                    'label' => 'Employment forms',
                    'classifier' => ClassifierTypeEnum::EMPLOYMENT_FORM->value,
                    'description' => 'If employee works on-site, remote or else.',
                    'constraint' => 'array, classifier keys',
                ],
                PositionFieldEnum::JOB_SEATS_NUM->value => [
                    'label' => 'Number of job seats',
                    'constraint' => 'integer, min. 1, max. 1000',
                ],
                PositionFieldEnum::DESCRIPTION->value => [
                    'label' => 'Description',
                    'description' => 'Responsibilities, job content, team, and working environment',
                    'constraint' => 'string, max. 2000 chars, structured text - text, lists (numbered, dashed), newlines, emojis',
                ],
                PositionFieldEnum::SALARY_FROM->value => [
                    'label' => 'Salary from',
                    'constraint' => 'integer, min. 0',
                ],
                PositionFieldEnum::SALARY_TO->value => [
                    'label' => 'Salary to',
                    'description' => 'Blank if the salary is not a range.',
                    'constraint' => 'integer, min. value is salary_from',
                ],
                PositionFieldEnum::SALARY_TYPE->value => [
                    'label' => 'Salary type',
                    'classifier' => ClassifierTypeEnum::SALARY_TYPE->value,
                    'description' => 'If salary is gross, net or else.',
                    'constraint' => 'string, classifier key',
                ],
                PositionFieldEnum::SALARY_FREQUENCY->value => [
                    'label' => 'Salary frequency',
                    'classifier' => ClassifierTypeEnum::SALARY_FREQUENCY->value,
                    'description' => 'If salary is paid monthly, daily or else.',
                    'constraint' => 'string, classifier key',
                ],
                PositionFieldEnum::SALARY_CURRENCY->value => [
                    'label' => 'Salary currency',
                    'classifier' => ClassifierTypeEnum::CURRENCY->value,
                    'constraint' => 'string, classifier key',
                ],
                PositionFieldEnum::SALARY_VAR->value => [
                    'label' => 'Salary variable addition',
                    'description' => 'Something extra like stock-based compensation.',
                    'constraint' => 'string'
                ],
                PositionFieldEnum::BENEFITS->value => [
                    'label' => 'Benefits',
                    'classifier' => ClassifierTypeEnum::BENEFIT->value,
                    'constraint' => 'array, classifier keys',
                ],
                PositionFieldEnum::MIN_EDUCATION_LEVEL->value => [
                    'label' => 'Required education level',
                    'classifier' => ClassifierTypeEnum::EDUCATION_LEVEL->value,
                    'constraint' => 'string, classifier key',
                ],
                PositionFieldEnum::EDUCATION_FIELD->value => [
                    'label' => 'Required education field',
                    'constraint' => 'string, max. 255 chars'
                ],
                PositionFieldEnum::SENIORITY->value => [
                    'label' => 'Required seniority',
                    'classifier' => ClassifierTypeEnum::SENIORITY->value,
                    'constraint' => 'array, classifier keys',
                ],
                PositionFieldEnum::EXPERIENCE->value => [
                    'label' => 'Required number of years of experience',
                    'constraint' => 'integer, min. 0, max. ',
                ],
                PositionFieldEnum::HARD_SKILLS->value => [
                    'label' => 'Required other hard skills',
                    'description' => 'Job certifications, programming languages, courses, technologies',
                    'constraint' => 'string, max. 2000 chars, structured text - text, lists (numbered, dashed), newlines, emojis',
                ],
                PositionFieldEnum::ORGANISATION_SKILLS->value => [
                    'label' => 'Required organisation skills',
                    'description' => 'scale 0-100; 0 = not required, 100 = very important',
                    'constraint' => 'integer, min. 0, max. 100',
                ],
                PositionFieldEnum::TEAM_SKILLS->value => [
                    'label' => 'Required team skills',
                    'description' => 'scale 0-100; 0 = not required, 100 = very important',
                    'constraint' => 'integer, min. 0, max. 100',
                ],
                PositionFieldEnum::TIME_MANAGEMENT->value => [
                    'label' => 'Required time management skills',
                    'description' => 'scale 0-100; 0 = not required, 100 = very important',
                    'constraint' => 'integer, min. 0, max. 100',
                ],
                PositionFieldEnum::COMMUNICATION_SKILLS->value => [
                    'label' => 'Required communication skills',
                    'description' => 'scale 0-100; 0 = not required, 100 = very important',
                    'constraint' => 'integer, min. 0, max. 100',
                ],
                PositionFieldEnum::LEADERSHIP->value => [
                    'label' => 'Required leadership skills',
                    'description' => 'scale 0-100; 0 = not required, 100 = very important',
                    'constraint' => 'integer, min. 0, max. 100',
                ],
                PositionFieldEnum::LANGUAGE_REQUIREMENTS->value => [
                    'label' => 'Language requirements',
                    'classifier' => [
                        ClassifierTypeEnum::LANGUAGE->value,
                        ClassifierTypeEnum::LANGUAGE_LEVEL->value,
                    ],
                    'constraint' => 'array, classifier key pairs "{language}-{language_level}"',
                    'example' => ['english-c2', 'czech-native']
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
