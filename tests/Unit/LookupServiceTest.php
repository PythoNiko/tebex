<?php

namespace Tests\Unit;

use App\Services\LookupService;
use App\Services\MinecraftLookupService;
use PHPUnit\Framework\TestCase;
use Mockery;

class LookupServiceTest extends TestCase
{
    protected $lookupService;
    protected $minecraftLookupServiceMock;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->minecraftLookupServiceMock = Mockery::mock(MinecraftLookupService::class);
        $this->lookupService = new LookupService([$this->minecraftLookupServiceMock]);
    }

    public function test_lookup_with_valid_username()
    {
        $params = ['username' => 'Test'];

        $this->minecraftLookupServiceMock
            ->shouldReceive('supports')
            ->once()
            ->with('minecraft')
            ->andReturn(true);
        
        $this->minecraftLookupServiceMock
            ->shouldReceive('lookup')
            ->once()
            ->with($params)
            ->andReturn([
                'username' => 'Test',
                'id' => 'd8d5a9237b2043d8883b1150148d6955',
                'avatar' => 'https://crafatar.com/avatars/d8d5a9237b2043d8883b1150148d6955',
            ]);

        $result = $this->lookupService->lookup('minecraft', $params);

        $this->assertEquals([
            'username' => 'Test',
            'id' => 'd8d5a9237b2043d8883b1150148d6955',
            'avatar' => 'https://crafatar.com/avatars/d8d5a9237b2043d8883b1150148d6955',
        ], $result);
    }

    public function test_lookup_throws_exception_when_no_platform_supports_type()
    {
        $params = ['username' => 'Test'];

        $this->minecraftLookupServiceMock
            ->shouldReceive('supports')
            ->once()
            ->with('minecraft')
            ->andReturn(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No lookup platform found for type: minecraft');

        $this->lookupService->lookup('minecraft', $params);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }
}
