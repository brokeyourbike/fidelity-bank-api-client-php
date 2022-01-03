# fidelity-bank-api-client

[![Latest Stable Version](https://img.shields.io/github/v/release/brokeyourbike/fidelity-bank-api-client-php)](https://github.com/brokeyourbike/fidelity-bank-api-client-php/releases)
[![Total Downloads](https://poser.pugx.org/brokeyourbike/fidelity-bank-api-client/downloads)](https://packagist.org/packages/brokeyourbike/fidelity-bank-api-client)
[![License: MPL-2.0](https://img.shields.io/badge/license-MPL--2.0-purple.svg)](https://github.com/brokeyourbike/fidelity-bank-api-client-php/blob/main/LICENSE)

[![Maintainability](https://api.codeclimate.com/v1/badges/ceea2bd24e191d6f91d7/maintainability)](https://codeclimate.com/github/brokeyourbike/fidelity-bank-api-client-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/ceea2bd24e191d6f91d7/test_coverage)](https://codeclimate.com/github/brokeyourbike/fidelity-bank-api-client-php/test_coverage)
[![tests](https://github.com/brokeyourbike/fidelity-bank-api-client-php/actions/workflows/tests.yml/badge.svg)](https://github.com/brokeyourbike/fidelity-bank-api-client-php/actions/workflows/tests.yml)

Fidelity Bank API Client for PHP

## Installation

```bash
composer require brokeyourbike/fidelity-bank-api-client
```

## Usage

```php
use BrokeYourBike\FidelityBank\Client;
use BrokeYourBike\FidelityBank\Interfaces\ConfigInterface;

assert($config instanceof ConfigInterface);
assert($httpClient instanceof \GuzzleHttp\ClientInterface);

$apiClient = new Client($config, $httpClient);
$apiClient->getTransactionStatus($transaction);
```

## License
[Mozilla Public License v2.0](https://github.com/brokeyourbike/fidelity-bank-api-client-php/blob/main/LICENSE)
