<?php

namespace Tests\Feature;

use Tests\TestCase;

class LookupControllerTest extends TestCase
{
    public function test_it_returns_bad_request_when_missing_type_or_id()
    {
        $response = $this->getJson('/lookup');

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'The `type` parameter is required, and either `username` or `id` must be provided.',
            'code' => 400,
        ]);
    }
}
