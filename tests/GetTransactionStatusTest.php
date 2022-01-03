<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\FidelityBank\Tests;

use Psr\Http\Message\ResponseInterface;
use Carbon\Carbon;
use BrokeYourBike\FidelityBank\Models\TransactionResponse;
use BrokeYourBike\FidelityBank\Interfaces\TransactionInterface;
use BrokeYourBike\FidelityBank\Interfaces\ConfigInterface;
use BrokeYourBike\FidelityBank\Enums\StatusCodeEnum;
use BrokeYourBike\FidelityBank\Enums\ErrorCodeEnum;
use BrokeYourBike\FidelityBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class GetTransactionStatusTest extends TestCase
{
    private string $username = 'unique-username';
    private string $password = 'secure-password';
    private string $reference = 'REF-123';

    protected function setUp(): void
    {
        parent::setUp();

        $currentTestDate = Carbon::create(2020, 1, 5, 23, 30, 59);
        Carbon::setTestNow($currentTestDate);
    }

    /** @test */
    public function it_can_prepare_request(): void
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getReference')->willReturn($this->reference);

        /** @var TransactionInterface $transaction */
        $this->assertInstanceOf(TransactionInterface::class, $transaction);

        $secretCode = $this->prepareSecretCode($this->username, $this->password);

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getUsername')->willReturn($this->username);
        $mockedConfig->method('getPassword')->willReturn($this->password);

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "Pin": "' . $this->reference . '",
                "AccountNumber": "13465798",
                "Status": "' . StatusCodeEnum::TRANSMIT->value . '",
                "ResponseCode": "' . ErrorCodeEnum::IN_PROGRESS->value . '",
                "ResponseMessage": "Request In Progress"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'GET',
            'https://api.example/payment/status',
            [
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'API_KEY' => $this->username,
                    'SECRET_CODE' => (string) $secretCode,
                ],
                \GuzzleHttp\RequestOptions::QUERY => [
                    'pin' => $this->reference,
                ],
            ],
        ])->once()->andReturn($mockedResponse);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);
        $requestResult = $api->getTransactionStatus($transaction);

        $this->assertInstanceOf(TransactionResponse::class, $requestResult);
    }
}
