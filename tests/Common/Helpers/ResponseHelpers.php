<?php

namespace Tests\Common\Helpers;

use App\Enums\ResponseCodeEnum;
use Illuminate\Testing\TestResponse;

function assertResponse(
    TestResponse $response,
    ResponseCodeEnum $code,
    ?array $data = null,
    ?string $message = null
): void {
    // assert correct HTTP status code
    $response->assertStatus($code->getStatusCode());

    // assert correct code in the response json
    $response->assertJsonPath('code', $code->name);

    if ($message !== null) {
        $response->assertJsonPath('message', $message);
    }

    if ($data !== null) {
        $response->assertJson(['data' => $data]);
    }
}
