<?php

declare(strict_types=1);

namespace App\Tests\Services;

use App\Services\CurrencyService;
use App\ValueObjects\CurrencyServiceRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyServiceTests extends TestCase
{

    private array $currency;
    /**
     * @var MockObject|HttpClientInterface
     */
    private MockObject|HttpClientInterface $client;

    protected function setUp(): void
    {
        $this->currency['EUR'] = ['code' => 'EUR'];
        $this->currency['PLN'] = ['code' => 'PLN'];
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->client->method('getHeaders')->willReturn(CurrencyServiceRequest::REQUEST_APPLICATION_CONTENT);
        $this->client->method('getCode')->willReturn(200);
        $this->client->method('toArray')->willReturn([0 => ['rates' => $this->currency]]);

        parent::setUp();
    }

    public function testGet()
    {
        $service = new CurrencyService($this->client);
        $this->assertSame($this->currency, $service->get());
    }

    public function testFilter()
    {
        $service = new CurrencyService($this->client);
        $this->assertSame($service->filter('EUR'), $this->currency['EUR']);
    }
}