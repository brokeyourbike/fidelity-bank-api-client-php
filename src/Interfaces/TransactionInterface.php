<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\FidelityBank\Interfaces;

use BrokeYourBike\FidelityBank\Interfaces\SenderInterface;
use BrokeYourBike\FidelityBank\Interfaces\RecipientInterface;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 */
interface TransactionInterface
{
    public function getSender(): ?SenderInterface;
    public function getRecipient(): ?RecipientInterface;
    public function getReference(): string;
    public function getRequestSuffix(): int;
    public function getDate(): \Carbon\CarbonInterface;
    public function getSendAmount(): float;
    public function getSendCurrencyCode(): string;
    public function getReceiveAmount(): float;
    public function getReceiveCurrencyCode(): string;
    public function getAccountNumber(): string;
    public function getBankCode(): string;
}
