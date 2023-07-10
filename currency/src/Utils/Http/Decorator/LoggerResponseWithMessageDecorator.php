<?php

declare(strict_types=1);

namespace App\Utils\Http\Decorator;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class LoggerResponseWithMessageDecorator implements HttpClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $currencyProviderResponseLogger,
        private string $additionalMessage = '',
    ) {
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $response = $this->httpClient->request($method, $url, $options);
        $this->currencyProviderResponseLogger->info($this->additionalMessage.$response->getContent());

        return $response;
    }

    public function stream(iterable|ResponseInterface $responses, float $timeout = null): ResponseStreamInterface
    {
        return $this->httpClient->stream($responses, $timeout);
    }

    public function withOptions(array $options): static
    {
        return $this->httpClient->withOptions($options);
    }
}
