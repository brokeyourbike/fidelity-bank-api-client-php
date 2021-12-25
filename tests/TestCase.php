<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\FidelityBank\Tests;

use Carbon\Carbon;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    protected function prepareSecretCode(string $username, string $password): string|false
    {
        return openssl_digest($username . Carbon::now()->format('Ymd') . $password, 'SHA256', false);
    }
}
