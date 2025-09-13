<?php

declare(strict_types=1);

use App\Enums\LanguageEnum;
use Domain\AI\Context\Mappers\CandidateMapper;
use Domain\AI\Context\Mappers\PositionMapper;
use Domain\AI\Enums\AIProviderEnum;
use Domain\AI\Scoring\Enums\ScoreCategoryEnum;
use Domain\Candidate\Enums\CandidateFieldEnum;
use Domain\Candidate\Enums\GenderEnum;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Enums\PositionFieldEnum;
use Domain\Position\Models\Position;
use AIProviders\Fake\FakeAIProvider;
use AIProviders\OpenAI\OpenAIProvider;
use Support\Classifier\Enums\ClassifierTypeEnum;

return [

    /*
    |--------------------------------------------------------------------------
    | Selected AI provider
    |--------------------------------------------------------------------------
    |
    | AI provider which is used for AI functions. Provider name should be
    | registered in the 'providers' array below.
    |
    */

    'provider' => env('AI_PROVIDER', AIProviderEnum::OPENAI->value),

    /*
    |--------------------------------------------------------------------------
    | AI providers
    |--------------------------------------------------------------------------
    |
    | List of AI providers. Each provider should implement AI provider
    | contract.
    |
    */

    'providers' => [
        AIProviderEnum::OPENAI->value => OpenAIProvider::class,
        AIProviderEnum::FAKE->value => FakeAIProvider::class,
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
            Candidate::class => CandidateMapper::class,
        ],
        'models' => [
            Candidate::class => [
                CandidateFieldEnum::FIRSTNAME->value => [
                    'label' => 'Firstname',
                    'schema' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ],
                ],
                CandidateFieldEnum::LASTNAME->value => [
                    'label' => 'Lastname',
                    'schema' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ],
                ],
                CandidateFieldEnum::GENDER->value => [
                    'label' => 'Gender',
                    'schema' => [
                        'type' => 'string',
                        'enum' => collect(GenderEnum::cases())->mapWithKeys(fn (GenderEnum $gender) => [$gender->value => __(sprintf('common.gender.%s', $gender->value))])->toArray(),
                    ],
                ],
                CandidateFieldEnum::LANGUAGE->value => [
                    'label' => 'Communication language',
                    'schema' => [
                        'type' => 'string',
                        'enum' => collect(LanguageEnum::cases())->mapWithKeys(fn (LanguageEnum $language) => [$language->value => __(sprintf('common.language.%s', $language->value))])->toArray(),
                    ]
                ],
                CandidateFieldEnum::EMAIL->value => [
                    'label' => 'Email',
                    'schema' => [
                        'type' => 'string',
                        'format' => 'email',
                        'maxLength' => 255
                    ]
                ],
                CandidateFieldEnum::PHONE_PREFIX->value => [
                    'label' => 'Phone prefix',
                    'schema' => [
                        'type' => 'string',
                        'classifier' => ClassifierTypeEnum::PHONE_PREFIX->value,
                    ],
                ],
                CandidateFieldEnum::PHONE_NUMBER->value => [
                    'label' => 'Phone number',
                    'schema' => [
                        'type' => 'string',
                        'maxLength' => 20,
                    ]
                ],
                CandidateFieldEnum::LINKEDIN->value => [
                    'label' => 'LinkedIn profile URL',
                    'schema' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ]
                ],
                CandidateFieldEnum::INSTAGRAM->value => [
                    'label' => 'Instagram profile URL',
                    'schema' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ]
                ],
                CandidateFieldEnum::GITHUB->value => [
                    'label' => 'Github profile URL',
                    'schema' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ]
                ],
                CandidateFieldEnum::PORTFOLIO->value => [
                    'label' => 'Portfolio/Personal web URL',
                    'schema' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ]
                ],
                CandidateFieldEnum::BIRTH_DATE->value => [
                    'label' => 'Birth date',
                    'schema' => [
                        'type' => 'string',
                        'format' => 'date',
                        'example' => '1999-01-05',
                    ],
                ],
                CandidateFieldEnum::EXPERIENCE->value => [
                    'label' => 'Working experience',
                    'schema' => [
                        'type' => 'array',
                        'description' => 'Sorted chronologically',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'position' => [
                                    'type' => 'string',
                                    'description' => 'Position name',
                                    'maxLength' => 50,
                                ],
                                'employer' => [
                                    'type' => ['string', 'null'],
                                    'description' => 'Employer name',
                                    'maxLength' => 50,
                                ],
                                'from' => [
                                    'type' => ['string', 'null'],
                                    'format' => 'date',
                                ],
                                'to' => [
                                    'type' => ['string', 'null'],
                                    'format' => 'date',
                                ],
                                'description' => [
                                    'type' => ['string', 'null'],
                                    'description' => 'brief description of key job attributes - responsibilities, job content, technologies, etc.',
                                    'maxLength' => 200,
                                ],
                            ]
                        ],
                        'example' => [
                            [
                                'position' => 'Fullstack developer',
                                'employer' => 'Alphabet Inc.',
                                'from' => '1998-01-01',
                                'to' => '2000-01-01',
                                'description' => 'development of internal ATS systems, MySQL, PHP, Laravel, Node.js, team of 10',
                            ]
                        ],
                    ],
                ],
                CandidateFieldEnum::TAGS->value => [
                    'label' => 'Tags',
                    'schema' => [
                        'type' => 'array',
                        'maxItems' => 10,
                        'items' => [
                            'type' => 'string',
                            'minLength' => 2,
                            'maxLength' => 30,
                        ],
                        'example' => ['mysql', 'typescript']
                    ],
                ],
            ],
            Position::class => [
                PositionFieldEnum::NAME->value => [
                    'label' => 'Name',
                    'schema' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ]
                ],
                PositionFieldEnum::DEPARTMENT->value => [
                    'label' => 'Department',
                    'schema' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ]
                ],
                PositionFieldEnum::FIELD->value => [
                    'label' => 'Field',
                    'schema' => [
                        'type' => 'string',
                        'classifier' => ClassifierTypeEnum::FIELD->value,
                    ]
                ],
                PositionFieldEnum::WORKLOADS->value => [
                    'label' => 'Workload',
                    'description' => 'If employee works full-time, part-time or else',
                    'schema' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                            'classifier' => ClassifierTypeEnum::WORKLOAD->value,
                        ]
                    ]
                ],
                PositionFieldEnum::EMPLOYMENT_RELATIONSHIPS->value => [
                    'label' => 'Employment relationship',
                    'description' => 'If the relationship is contract, internship or else',
                    'schema' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                            'classifier' => ClassifierTypeEnum::EMPLOYMENT_RELATIONSHIP->value,
                        ]
                    ]
                ],
                PositionFieldEnum::EMPLOYMENT_FORMS->value => [
                    'label' => 'Employment forms',
                    'description' => 'If employee works on-site, remote or else',
                    'schema' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                            'classifier' => ClassifierTypeEnum::EMPLOYMENT_FORM->value,
                        ]
                    ]
                ],
                PositionFieldEnum::JOB_SEATS_NUM->value => [
                    'label' => 'Number of job seats',
                    'schema' => [
                        'type' => 'integer',
                        'min' => 1,
                        'max' => 1000,
                    ]
                ],
                PositionFieldEnum::DESCRIPTION->value => [
                    'label' => 'Description',
                    'description' => 'Responsibilities, job content, team, and working environment',
                    'schema' => [
                        'type' => 'string',
                        'description' => 'structured text - text, lists (numbered, dashed), newlines, emojis',
                        'maxLength' => 2000,
                    ]
                ],
                PositionFieldEnum::SALARY_FROM->value => [
                    'label' => 'Salary from',
                    'schema' => [
                        'type' => 'integer',
                        'min' => 0,
                    ],
                ],
                PositionFieldEnum::SALARY_TO->value => [
                    'label' => 'Salary to',
                    'description' => 'Blank if the salary is not a range',
                    'schema' => [
                        'type' => 'integer',
                        'min' => 0,
                    ],
                ],
                PositionFieldEnum::SALARY_TYPE->value => [
                    'label' => 'Salary type',
                    'description' => 'If salary is gross, net or else',
                    'schema' => [
                        'type' => 'string',
                        'classifier' => ClassifierTypeEnum::SALARY_TYPE->value,
                    ]
                ],
                PositionFieldEnum::SALARY_FREQUENCY->value => [
                    'label' => 'Salary frequency',
                    'description' => 'If salary is paid monthly, daily or else',
                    'schema' => [
                        'type' => 'string',
                        'classifier' => ClassifierTypeEnum::SALARY_FREQUENCY->value,
                    ]
                ],
                PositionFieldEnum::SALARY_CURRENCY->value => [
                    'label' => 'Salary currency',
                    'schema' => [
                        'type' => 'string',
                        'classifier' => ClassifierTypeEnum::CURRENCY->value,
                    ]
                ],
                PositionFieldEnum::SALARY_VAR->value => [
                    'label' => 'Salary variable addition',
                    'description' => 'Something extra like stock-based compensation',
                    'schema' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ]
                ],
                PositionFieldEnum::BENEFITS->value => [
                    'label' => 'Benefits',
                    'schema' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                            'classifier' => ClassifierTypeEnum::BENEFIT->value,
                        ]
                    ]
                ],
                PositionFieldEnum::MIN_EDUCATION_LEVEL->value => [
                    'label' => 'Required education level',
                    'constraint' => 'string, classifier key',
                    'schema' => [
                        'type' => 'string',
                        'classifier' => ClassifierTypeEnum::EDUCATION_LEVEL->value,
                    ]
                ],
                PositionFieldEnum::EDUCATION_FIELD->value => [
                    'label' => 'Required education field',
                    'schema' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ]
                ],
                PositionFieldEnum::SENIORITY->value => [
                    'label' => 'Required seniority',
                    'description' => 'Only for IT positions',
                    'schema' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                            'classifier' => ClassifierTypeEnum::SENIORITY->value,
                        ]
                    ]
                ],
                PositionFieldEnum::EXPERIENCE->value => [
                    'label' => 'Required number of years of experience',
                    'schema' => [
                        'type' => 'integer',
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                PositionFieldEnum::HARD_SKILLS->value => [
                    'label' => 'Required other hard skills',
                    'description' => 'Job certifications, programming languages, courses, technologies',
                    'schema' => [
                        'type' => 'string',
                        'description' => 'structured text - text, lists (numbered, dashed), newlines, emojis',
                        'maxLength' => 2000,
                    ]
                ],
                PositionFieldEnum::ORGANISATION_SKILLS->value => [
                    'label' => 'Required organisation skills',
                    'schema' => [
                        'type' => 'integer',
                        'description' => 'scale 0-100, 0 = not required, 100 = important',
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                PositionFieldEnum::TEAM_SKILLS->value => [
                    'label' => 'Required team skills',
                    'schema' => [
                        'type' => 'integer',
                        'description' => 'scale 0-100, 0 = not required, 100 = important',
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                PositionFieldEnum::TIME_MANAGEMENT->value => [
                    'label' => 'Required time management skills',
                    'schema' => [
                        'type' => 'integer',
                        'description' => 'scale 0-100, 0 = not required, 100 = important',
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                PositionFieldEnum::COMMUNICATION_SKILLS->value => [
                    'label' => 'Required communication skills',
                    'schema' => [
                        'type' => 'integer',
                        'description' => 'scale 0-100, 0 = not required, 100 = important',
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                PositionFieldEnum::LEADERSHIP->value => [
                    'label' => 'Required leadership skills',
                    'schema' => [
                        'type' => 'integer',
                        'description' => 'scale 0-100, 0 = not required, 100 = important',
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                PositionFieldEnum::LANGUAGE_REQUIREMENTS->value => [
                    'label' => 'Language requirements',
                    'schema' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'language' => [
                                    'type' => 'string',
                                    'classifier' => ClassifierTypeEnum::LANGUAGE->value,
                                ],
                                'level' => [
                                    'type' => 'string',
                                    'classifier' => ClassifierTypeEnum::LANGUAGE_LEVEL->value,
                                ],
                            ],
                        ],
                        'example' => [
                            ['language' => 'english', 'level' => 'c2'],
                            ['language' => 'czech', 'level' => 'native'],
                        ]
                    ],
                ],
                PositionFieldEnum::TAGS->value => [
                    'label' => 'Tags',
                    'schema' => [
                        'type' => 'array',
                        'maxItems' => 10,
                        'items' => [
                            'type' => 'string',
                            'minLength' => 2,
                            'maxLength' => 30,
                        ],
                        'example' => ['mysql', 'typescript']
                    ],
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
