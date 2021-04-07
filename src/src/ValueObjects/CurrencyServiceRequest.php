<?php

declare(strict_types=1);

namespace App\ValueObjects;

class CurrencyServiceRequest
{
    public const API_URL = 'http://api.nbp.pl/api/exchangerates/tables/A/';
    public const REQUEST_METHOD = 'GET';
    public const REQUEST_APPLICATION_CONTENT = 'application/json';
    public const FILTER_KEY = 'code';
}
