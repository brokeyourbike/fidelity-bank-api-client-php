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
 * @method static ErrorCode PAID()
 * @method static ErrorCode INVALID_ACCOUNT()
 * @method static ErrorCode NOT_PERMITTED()
 * @method static ErrorCode TRANSACTION_LIMIT()
 * @method static ErrorCode INSUFFICIENT_FUNDS()
 * @method static ErrorCode DUPLICATE_TRANSACTION()
 * @method static ErrorCode INVALID_RECIPIENT()
 * @method static ErrorCode AUTH_FAILED()
 * @method static ErrorCode SYSTEM_MALFUNCTION()
 * @method static ErrorCode IN_PROGRESS()
 * @method static ErrorCode NAME_MISMATCH()
 * @method static ErrorCode INVALID_PIN()
 * @method static ErrorCode INVALID_BANK_CODE()
 * @method static ErrorCode INVALID_BANK()
 * @method static ErrorCode ACCOUNT_NOT_FOUND()
 * @method static ErrorCode INVALID_ACCOUNT_STATUS()
 * @psalm-immutable
 */
final class ErrorCodeEnum extends \MyCLabs\Enum\Enum
{
    /**
     * Paid.
     */
    private const PAID = '00';

    /**
     * Invalid Account.
     */
    private const INVALID_ACCOUNT = '01';

    /**
     * Transaction Not Permitted.
     */
    private const NOT_PERMITTED = '02';

    /**
     * Transaction Limit Exceeded.
     */
    private const TRANSACTION_LIMIT = '03';

    /**
     * Insufficient Fund.
     */
    private const INSUFFICIENT_FUNDS = '04';

    /**
     * Duplicate Tranmission.
     */
    private const DUPLICATE_TRANSACTION = '09';

    /**
     * Invalid Beneficiary.
     */
    private const INVALID_RECIPIENT = '10';

    /**
     * Authentication Failed.
     */
    private const AUTH_FAILED = '36';

    /**
     * System Exception.
     */
    private const SYSTEM_EXCEPTION = '47';

    /**
     * System Malfunction.
     */
    private const SYSTEM_MALFUNCTION = '48';

    /**
     * Request In Progress.
     */
    private const IN_PROGRESS = '60';

    /**
     * Account Name Mismatch.
     */
    private const NAME_MISMATCH = '61';

    /**
     * Invalid Pin Number.
     */
    private const INVALID_PIN = '62';

    /**
     * Invalid Bank Code.
     */
    private const INVALID_BANK_CODE = '63';

    /**
     * Invalid Bank.
     */
    private const INVALID_BANK = '64';

    /**
     * Account does not exist.
     */
    private const ACCOUNT_NOT_FOUND = '65';

    /**
     * Account status invalid.
     */
    private const INVALID_ACCOUNT_STATUS = '66';
}
