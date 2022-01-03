<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\FidelityBank\Models;

use Spatie\DataTransferObject\Attributes\MapFrom;
use BrokeYourBike\DataTransferObject\JsonResponse;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class TransactionResponse extends JsonResponse
{
    #[MapFrom('Status')]
    public string $status;

    #[MapFrom('ResponseCode')]
    public string $responseCode;

    #[MapFrom('ResponseMessage')]
    public string $responseMessage;

    #[MapFrom('Pin')]
    public ?string $pin;

    #[MapFrom('AccountNumber')]
    public ?string $accountNumber;
}
