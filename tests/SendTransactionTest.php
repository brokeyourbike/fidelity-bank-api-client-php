<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\FidelityBank\Tests;

use Psr\Http\Message\ResponseInterface;
use Carbon\Carbon;
use BrokeYourBike\FidelityBank\Interfaces\TransactionInterface;
use BrokeYourBike\FidelityBank\Interfaces\SenderInterface;
use BrokeYourBike\FidelityBank\Interfaces\RecipientInterface;
use BrokeYourBike\FidelityBank\Interfaces\ConfigInterface;
use BrokeYourBike\FidelityBank\Exceptions\PrepareRequestException;
use BrokeYourBike\FidelityBank\Enums\StatusCodeEnum;
use BrokeYourBike\FidelityBank\Enums\ErrorCodeEnum;
use BrokeYourBike\FidelityBank\Client;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 */
class SendTransactionTest extends TestCase
{
    private string $username = 'unique-username';
    private string $password = 'secure-password';
    private SenderInterface $sender;
    private RecipientInterface $recipient;

    protected function setUp(): void
    {
        parent::setUp();

        $currentTestDate = Carbon::create(2020, 1, 5, 23, 30, 59);
        Carbon::setTestNow($currentTestDate);

        $this->sender = $this->getMockBuilder(SenderInterface::class)->getMock();
        $this->recipient = $this->getMockBuilder(RecipientInterface::class)->getMock();
    }

    /** @test */
    public function it_will_throw_if_no_sender_in_transaction()
    {
        /** @var TransactionInterface $transaction */
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();

        $this->assertNull($transaction->getSender());

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedClient = $this->getMockBuilder(\GuzzleHttp\ClientInterface::class)->getMock();
        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();

        $this->expectExceptionMessage(SenderInterface::class . ' is required');
        $this->expectException(PrepareRequestException::class);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);

        $api->sendTransaction($transaction);
    }

    /** @test */
    public function it_will_throw_if_no_recipient_in_transaction()
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getSender')->willReturn($this->sender);

        /** @var TransactionInterface $transaction */
        $this->assertNull($transaction->getRecipient());

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedClient = $this->getMockBuilder(\GuzzleHttp\ClientInterface::class)->getMock();
        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();

        $this->expectExceptionMessage(RecipientInterface::class . ' is required');
        $this->expectException(PrepareRequestException::class);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);

        $api->sendTransaction($transaction);
    }

    /**
     * @test
     * @dataProvider isLiveProvider
     */
    public function it_can_prepare_request(bool $isLive): void
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getSender')->willReturn($this->sender);
        $transaction->method('getRecipient')->willReturn($this->recipient);
        $transaction->method('getDate')->willReturn(Carbon::now());

        /** @var TransactionInterface $transaction */
        $this->assertInstanceOf(TransactionInterface::class, $transaction);

        $secretCode = $this->prepareSecretCode($this->username, $this->password);

        $requestId = $this->username . Carbon::now()->format('YmdHis') . sprintf('%04d', $transaction->getRequestSuffix());

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('isLive')->willReturn($isLive);
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getUsername')->willReturn($this->username);
        $mockedConfig->method('getPassword')->willReturn($this->password);

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "Pin": "' . $transaction->getReference() . '",
                "AccountNumber": "' . $transaction->getAccountNumber() . '",
                "Status": "' . (string) StatusCodeEnum::TRANSMIT() . '",
                "ResponseCode": "' . (string) ErrorCodeEnum::IN_PROGRESS() . '",
                "ResponseMessage": "Request In Progress"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/payment/deposit',
            [
                \GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'API_KEY' => $this->username,
                    'SECRET_CODE' => (string) $secretCode,
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'RequestID' => $requestId,
                    'Pin' => $transaction->getReference(),
                    'DateTimeLocal' => (string) Carbon::now()->toISOString(),
                    'DateTimeUTC' => (string) Carbon::now()->setTimezone('UTC')->toISOString(),
                    'TransactionDate' => $transaction->getDate()->toISOString(),
                    'SendAmount' => $transaction->getSendAmount(),
                    'SendAmountCurrency' => $transaction->getSendCurrencyCode(),
                    'ReceiveAmount' => $transaction->getReceiveAmount(),
                    'ReceiveAmountCurrency' => $transaction->getReceiveCurrencyCode(),
                    'AccountNumber' => $transaction->getAccountNumber(),
                    'BankCode' => $transaction->getBankCode(),

                    'SenderFirstName' => $this->sender->getFirstName(),
                    'SenderMiddleName' => $this->sender->getMiddleName(),
                    'SenderLastName' => $this->sender->getLastName(),
                    'SenderAddress' =>'-',
                    'SenderCity' => '-',
                    'SenderState' => '-',
                    'SenderCountry' => $this->sender->getCountryCode(),
                    'SenderPhoneNo' => '-',
                    'SenderZip' => '-',

                    'ReceiverFirstName' => $this->recipient->getFirstName(),
                    'ReceiverMiddleName' => $this->recipient->getMiddleName(),
                    'ReceiverLastName' => $this->recipient->getLastName(),
                    'ReceiverAddress' => '-',
                    'ReceiverCity' => '-',
                    'ReceiverState' => '-',
                    'ReceiverCountry' => $this->recipient->getCountryCode(),
                    'ReceiverPhoneNo' => '-',
                    'ReceiverZip' => '-',
                ],
            ],
        ])->once()->andReturn($mockedResponse);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);
        $requestResult = $api->sendTransaction($transaction);

        $this->assertInstanceOf(ResponseInterface::class, $requestResult);
    }

    /**
     * @test
     * @dataProvider isLiveProvider
     */
    public function it_will_pass_source_model_as_option(bool $isLive): void
    {
        $transaction = $this->getMockBuilder(SourceTransactionFixture::class)->getMock();
        $transaction->method('getSender')->willReturn($this->sender);
        $transaction->method('getRecipient')->willReturn($this->recipient);
        $transaction->method('getDate')->willReturn(Carbon::now());

        /** @var TransactionInterface $transaction */
        $this->assertInstanceOf(TransactionInterface::class, $transaction);

        $secretCode = $this->prepareSecretCode($this->username, $this->password);

        $requestId = $this->username . Carbon::now()->format('YmdHis') . sprintf('%04d', $transaction->getRequestSuffix());

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('isLive')->willReturn($isLive);
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getUsername')->willReturn($this->username);
        $mockedConfig->method('getPassword')->willReturn($this->password);

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/payment/deposit',
            [
                \GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'API_KEY' => $this->username,
                    'SECRET_CODE' => (string) $secretCode,
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'RequestID' => $requestId,
                    'Pin' => $transaction->getReference(),
                    'DateTimeLocal' => (string) Carbon::now()->toISOString(),
                    'DateTimeUTC' => (string) Carbon::now()->setTimezone('UTC')->toISOString(),
                    'TransactionDate' => $transaction->getDate()->toISOString(),
                    'SendAmount' => $transaction->getSendAmount(),
                    'SendAmountCurrency' => $transaction->getSendCurrencyCode(),
                    'ReceiveAmount' => $transaction->getReceiveAmount(),
                    'ReceiveAmountCurrency' => $transaction->getReceiveCurrencyCode(),
                    'AccountNumber' => $transaction->getAccountNumber(),
                    'BankCode' => $transaction->getBankCode(),

                    'SenderFirstName' => $this->sender->getFirstName(),
                    'SenderMiddleName' => $this->sender->getMiddleName(),
                    'SenderLastName' => $this->sender->getLastName(),
                    'SenderAddress' =>'-',
                    'SenderCity' => '-',
                    'SenderState' => '-',
                    'SenderCountry' => $this->sender->getCountryCode(),
                    'SenderPhoneNo' => '-',
                    'SenderZip' => '-',

                    'ReceiverFirstName' => $this->recipient->getFirstName(),
                    'ReceiverMiddleName' => $this->recipient->getMiddleName(),
                    'ReceiverLastName' => $this->recipient->getLastName(),
                    'ReceiverAddress' => '-',
                    'ReceiverCity' => '-',
                    'ReceiverState' => '-',
                    'ReceiverCountry' => $this->recipient->getCountryCode(),
                    'ReceiverPhoneNo' => '-',
                    'ReceiverZip' => '-',
                ],
                \BrokeYourBike\HasSourceModel\Enums\RequestOptions::SOURCE_MODEL => $transaction,
            ],
        ])->once();

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);
        $requestResult = $api->sendTransaction($transaction);

        $this->assertInstanceOf(ResponseInterface::class, $requestResult);
    }
}
