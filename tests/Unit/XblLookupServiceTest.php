<?php

namespace Tests\Unit;

use App\Services\Lookup\XblLookupService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class XblLookupServiceTest extends TestCase
{
    protected XblLookupService $service;
    private $avatar = 'https://avatar-ssl.xboxlive.com/avatar/2533274884045330/avatarpic-l.png';

    protected function setUp(): void
    {
        parent::setUp();

        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'id' => '1234',
                'username' => 'xbl_user',
                'meta' => [
                    'avatar' => $this->avatar,
                ],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $mockClient = new Client(['handler' => $handlerStack]);

        $this->service = new XblLookupService($mockClient);
    }

    public function test_it_returns_expected_data()
    {
        $result = $this->service->lookup(['id' => '1234']);

        $this->assertEquals([
            'username' => 'xbl_user',
            'id' => '1234',
            'avatar' => $this->avatar,
        ], $result);
    }
}
