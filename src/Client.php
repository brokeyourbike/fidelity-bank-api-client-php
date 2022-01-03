<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\FidelityBank;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;
use Carbon\Carbon;
use BrokeYourBike\ResolveUri\ResolveUriTrait;
use BrokeYourBike\HttpEnums\HttpMethodEnum;
use BrokeYourBike\HttpClient\HttpClientTrait;
use BrokeYourBike\HttpClient\HttpClientInterface;
use BrokeYourBike\HasSourceModel\SourceModelInterface;
use BrokeYourBike\HasSourceModel\HasSourceModelTrait;
use BrokeYourBike\HasSourceModel\HasSourceModelInterface;
use BrokeYourBike\FidelityBank\Models\TransactionResponse;
use BrokeYourBike\FidelityBank\Interfaces\TransactionInterface;
use BrokeYourBike\FidelityBank\Interfaces\SenderInterface;
use BrokeYourBike\FidelityBank\Interfaces\RecipientInterface;
use BrokeYourBike\FidelityBank\Interfaces\ConfigInterface;
use BrokeYourBike\FidelityBank\Exceptions\PrepareRequestException;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class Client implements HttpClientInterface, HasSourceModelInterface
{
    use HttpClientTrait;
    use ResolveUriTrait;
    use HasSourceModelTrait;

    private ConfigInterface $config;

    public function __construct(ConfigInterface $config, ClientInterface $httpClient)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function sendTransaction(TransactionInterface $transaction): TransactionResponse
    {
        $sender = $transaction->getSender();
        $recipient = $transaction->getRecipient();

        if (!$sender instanceof SenderInterface) {
            throw PrepareRequestException::noSender($transaction);
        }

        if (!$recipient instanceof RecipientInterface) {
            throw PrepareRequestException::noRecipient($transaction);
        }

        if ($transaction instanceof SourceModelInterface) {
            $this->setSourceModel($transaction);
        }

        $response = $this->performRequest(HttpMethodEnum::POST, 'payment/deposit', [
            'RequestID' => $this->prepareRequestId($transaction),
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

            'SenderFirstName' => $sender->getFirstName(),
            'SenderMiddleName' => $sender->getMiddleName(),
            'SenderLastName' => $sender->getLastName(),
            'SenderAddress' => $sender->getAddress() ?? '-',
            'SenderCity' => $sender->getCity() ?? '-',
            'SenderState' => $sender->getState() ?? '-',
            'SenderCountry' => $sender->getCountryCode(),
            'SenderPhoneNo' => $sender->getPhoneNumber() ?? '-',
            'SenderZip' => $sender->getPostalCode() ?? '-',

            'ReceiverFirstName' => $recipient->getFirstName(),
            'ReceiverMiddleName' => $recipient->getMiddleName(),
            'ReceiverLastName' => $recipient->getLastName(),
            'ReceiverAddress' => $recipient->getAddress() ?? '-',
            'ReceiverCity' => $recipient->getCity() ?? '-',
            'ReceiverState' => $recipient->getState() ?? '-',
            'ReceiverCountry' => $recipient->getCountryCode(),
            'ReceiverPhoneNo' => $recipient->getPhoneNumber() ?? '-',
            'ReceiverZip' => $recipient->getPostalCode() ?? '-',
        ]);

        return new TransactionResponse($response);
    }

    public function getTransactionStatus(TransactionInterface $transaction): TransactionResponse
    {
        if ($transaction instanceof SourceModelInterface) {
            $this->setSourceModel($transaction);
        }

        $response = $this->performRequest(HttpMethodEnum::GET, 'payment/status', [
            'pin' => $transaction->getReference(),
        ]);

        return new TransactionResponse($response);
    }

    /**
     * @param HttpMethodEnum $method
     * @param string $uri
     * @param array<mixed> $data
     * @return ResponseInterface
     *
     * @throws \Exception
     */
    private function performRequest(HttpMethodEnum $method, string $uri, array $data): ResponseInterface
    {
        $option = match($method) {
            HttpMethodEnum::GET => \GuzzleHttp\RequestOptions::QUERY,
            default => \GuzzleHttp\RequestOptions::JSON,
        };

        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'API_KEY' => $this->config->getUsername(),
                'SECRET_CODE' => $this->prepareSecretCode(),
            ],
            $option => $data,
        ];

        if ($this->getSourceModel()) {
            $options[\BrokeYourBike\HasSourceModel\Enums\RequestOptions::SOURCE_MODEL] = $this->getSourceModel();
        }

        $uri = (string) $this->resolveUriFor($this->config->getUrl(), $uri);
        return $this->httpClient->request($method->value, $uri, $options);
    }

    private function prepareSecretCode(): string
    {
        $secretCodeData = [
            $this->config->getUsername(),
            Carbon::now()->format('Ymd'),
            $this->config->getPassword(),
        ];

        return hash('sha256', implode('', $secretCodeData), false);
    }

    private function prepareRequestId(TransactionInterface $transaction): string
    {
        return $this->config->getUsername() .
            Carbon::now()->format('YmdHis') .
            sprintf('%04d', $transaction->getRequestSuffix());
    }
}
