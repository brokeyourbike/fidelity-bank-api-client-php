<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\FidelityBank\Exceptions;

use BrokeYourBike\FidelityBank\Interfaces\TransactionInterface;
use BrokeYourBike\FidelityBank\Interfaces\SenderInterface;
use BrokeYourBike\FidelityBank\Interfaces\RecipientInterface;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
final class PrepareRequestException extends \RuntimeException
{
    private TransactionInterface $transaction;

    protected function __construct(TransactionInterface $transaction, string $message, ?\Throwable $previous = null)
    {
        $this->transaction = $transaction;

        parent::__construct($message, 0, $previous);
    }

    public function getTransaction(): TransactionInterface
    {
        return $this->transaction;
    }

    public static function noSender(TransactionInterface $transaction): self
    {
        $className = $transaction::class;
        $senderClassName = SenderInterface::class;
        return new static($transaction, "{$senderClassName} is required for {$className} `{$transaction->getReference()}`");
    }

    public static function noRecipient(TransactionInterface $transaction): self
    {
        $className = $transaction::class;
        $recipientClassName = RecipientInterface::class;
        return new static($transaction, "{$recipientClassName} is required for {$className} `{$transaction->getReference()}`");
    }
}
