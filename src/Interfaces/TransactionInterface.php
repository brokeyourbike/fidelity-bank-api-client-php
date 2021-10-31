<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\FidelityBank\Interfaces;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 */
interface TransactionInterface
{
    public function getReference(): string;
}
