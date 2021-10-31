<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\FidelityBank\Enums;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 *
 * @method static StatusCode TRANSMIT()
 * @method static StatusCode IN_PROGRESS()
 * @method static StatusCode PAID()
 * @method static StatusCode CANCELED()
 * @method static StatusCode ERROR()
 * @psalm-immutable
 */
final class StatusCodeEnum extends \MyCLabs\Enum\Enum
{
    /**
     * Transaction transmit.
     */
    private const TRANSMIT = 'TRANSMIT';

    /**
     * Beneficiary payment in progress.
     */
    private const IN_PROGRESS = 'IN_PROGRESS';

    /**
     * Transaction paid.
     */
    private const PAID = 'PAID';

    /**
     * Transaction has been canceled.
     */
    private const CANCELED = 'CANCELLED';

    /**
     * Transaction failed.
     */
    private const ERROR = 'ERROR';
}
