<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class LookupControllerTest extends TestCase
{
    public function test_lookup_with_valid_username_returns_success()
    {
        Http::fake([
            'https://api.mojang.com/users/profiles/minecraft/*' => Http::response(['id' => '12345', 'name' => 'player1'], 200),
        ]);

        $response = $this->getJson('/lookup?type=minecraft&username=Test');

        $response->assertStatus(200)
            ->assertJson([
                'username' => 'Test',
                'id' => 'd8d5a9237b2043d8883b1150148d6955',
                'avatar' => 'https://crafatar.com/avatars/d8d5a9237b2043d8883b1150148d6955',
            ]);
    }
    
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
