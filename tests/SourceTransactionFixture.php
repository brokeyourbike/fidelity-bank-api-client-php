<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\FidelityBank\Tests;

use BrokeYourBike\HasSourceModel\SourceModelInterface;
use BrokeYourBike\FidelityBank\Interfaces\TransactionInterface;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
abstract class SourceTransactionFixture implements TransactionInterface, SourceModelInterface
{}
