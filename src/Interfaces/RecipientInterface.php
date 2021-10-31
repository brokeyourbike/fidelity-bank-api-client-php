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
interface RecipientInterface
{
    public function getFirstName(): string;
    public function getMiddleName(): string;
    public function getLastName(): string;
    public function getAddress(): ?string;
    public function getCity(): ?string;
    public function getState(): ?string;

    /**
     * ISO-2 country code
     *
     * @return string
     */
    public function getCountryCode(): string;

    public function getPhoneNumber(): ?string;
    public function getPostalCode(): ?string;
}
