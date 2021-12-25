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
enum ErrorCodeEnum: string
{
    /**
     * Paid.
     */
    case PAID = '00';

    /**
     * Invalid Account.
     */
    case INVALID_ACCOUNT = '01';

    /**
     * Transaction Not Permitted.
     */
    case NOT_PERMITTED = '02';

    /**
     * Transaction Limit Exceeded.
     */
    case TRANSACTION_LIMIT = '03';

    /**
     * Insufficient Fund.
     */
    case INSUFFICIENT_FUNDS = '04';

    /**
     * Duplicate Tranmission.
     */
    case DUPLICATE_TRANSACTION = '09';

    /**
     * Invalid Beneficiary.
     */
    case INVALID_RECIPIENT = '10';

    /**
     * Authentication Failed.
     */
    case AUTH_FAILED = '36';

    /**
     * System Exception.
     */
    case SYSTEM_EXCEPTION = '47';

    /**
     * System Malfunction.
     */
    case SYSTEM_MALFUNCTION = '48';

    /**
     * Request In Progress.
     */
    case IN_PROGRESS = '60';

    /**
     * Account Name Mismatch.
     */
    case NAME_MISMATCH = '61';

    /**
     * Invalid Pin Number.
     */
    case INVALID_PIN = '62';

    /**
     * Invalid Bank Code.
     */
    case INVALID_BANK_CODE = '63';

    /**
     * Invalid Bank.
     */
    case INVALID_BANK = '64';

    /**
     * Account does not exist.
     */
    case ACCOUNT_NOT_FOUND = '65';

    /**
     * Account status invalid.
     */
    case INVALID_ACCOUNT_STATUS = '66';
}
