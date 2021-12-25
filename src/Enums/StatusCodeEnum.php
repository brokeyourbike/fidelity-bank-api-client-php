<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\FidelityBank\Enums;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
enum StatusCodeEnum: string
{
    /**
     * Transaction transmit.
     */
    case TRANSMIT = 'TRANSMIT';

    /**
     * Beneficiary payment in progress.
     */
    case IN_PROGRESS = 'IN_PROGRESS';

    /**
     * Transaction paid.
     */
    case PAID = 'PAID';

    /**
     * Transaction has been canceled.
     */
    case CANCELED = 'CANCELLED';

    /**
     * Transaction failed.
     */
    case ERROR = 'ERROR';
}
