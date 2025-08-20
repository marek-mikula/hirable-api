<?php

declare(strict_types=1);

use Services\OpenAI\Enums\PromptEnum;

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API Key and organization. This will be
    | used to authenticate with the OpenAI API - you can find your API key
    | and organization on your OpenAI dashboard, at https://openai.com.
    */

    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Project
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API project. This is used optionally in
    | situations where you are using a legacy user API key and need association
    | with a project. This is not required for the newer API keys.
    */
    'project' => env('OPENAI_PROJECT'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Base URL
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API base URL used to make requests. This
    | is needed if using a custom API endpoint. Defaults to: api.openai.com/v1
    */
    'base_uri' => env('OPENAI_BASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout may be used to specify the maximum number of seconds to wait
    | for a response. By default, the client will time out after 30 seconds.
    */

    'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Model
    |--------------------------------------------------------------------------
    |
    | The model is used to specify the model type name to use for inference.
    */

    'model' => env('OPENAI_MODEL'),

    /*
    |--------------------------------------------------------------------------
    | Custom model for specific use case
    |--------------------------------------------------------------------------
    |
    | Here you max specify if different model should be used for specific
    | use case defined by the prompt.
    */

    'models' => [],

    /*
    |--------------------------------------------------------------------------
    | Prompts
    |--------------------------------------------------------------------------
    |
    | Map of prompt IDs to use in the API.
    */

    'prompts' => [
        PromptEnum::EXTRACT_CV_DATA->value => [
            'id' => 'pmpt_687bc65410a481909fbf6bcd4c18bdac09a3e37f2101523e',
            'version' => '12',
        ],
        PromptEnum::EVALUATE_CANDIDATE->value => [
            'id' => 'pmpt_687e176904f8819687d39433423fed140a31159dc4e9c7d9',
            'version' => '14',
        ],
        PromptEnum::GENERATE_POSITION_FROM_PROMPT->value => [
            'id' => 'pmpt_689deeac979481969b277fdcdcd1ca9000bae19d7a9634c2',
            'version' => '12',
        ],
        PromptEnum::GENERATE_POSITION_FROM_FILE->value => [
            'id' => 'pmpt_68a0ad66153c8197b7393ee6f26c049c0a6ccd261a1ec1f7',
            'version' => '6',
        ],
    ],
];
