<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\WrongContentTypeException;
use App\Exceptions\WrongResponseCodeException;
use App\Exceptions\WrongResponseStructureException;
use App\ValueObjects\CurrencyServiceRequest;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CurrencyService
{
    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;
    /**
     * @var mixed
     */
    private array $content;

    public function __construct(HttpClientInterface $client)
    {
        $this->httpClient = $client;
    }

    /**
     * @return array
     * @throws WrongContentTypeException
     * @throws WrongResponseCodeException
     * @throws WrongResponseStructureException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function get(): array
    {
        $response = $this->httpClient->request(CurrencyServiceRequest::REQUEST_METHOD, CurrencyServiceRequest::API_URL);
        $this->verifyResponse($response);

        $this->content = $response->toArray()[0]["rates"];
        
        return $this->content;
    }

    /**
     * @param string $code
     * @return array
     */
    public function filter(string $code): array
    {
        if (!isset($this->content)) {
            $this->get();
        }
        return array_filter($this->content, function ($currency) use ($code) {
            return $currency['code'] == $code;
        });
    }

    /**
     * @param ResponseInterface $response
     * @throws WrongContentTypeException
     * @throws WrongResponseCodeException
     * @throws WrongResponseStructureException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function verifyResponse(ResponseInterface $response): void
    {
        $code = $response->getStatusCode();

        if (200 != $code) {
            throw new WrongResponseCodeException();
        }

        $contentType = $response->getHeaders()['content-type'][0];

        if (strpos($contentType, CurrencyServiceRequest::REQUEST_APPLICATION_CONTENT)) {
            throw new WrongContentTypeException();
        }

        if (!isset($response->toArray()[0]["rates"])) {
            throw new WrongResponseStructureException();
        }
    }
}
