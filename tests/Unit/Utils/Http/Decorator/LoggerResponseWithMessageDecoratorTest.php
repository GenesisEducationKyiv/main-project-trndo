<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils\Http\Decorator;

use App\Utils\Http\Decorator\LoggerResponseWithMessageDecorator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class LoggerResponseWithMessageDecoratorTest extends TestCase
{
    public function testRequestLogsContentWithAdditionalMessage(): void
    {
        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->once())
            ->method('info')
            ->with('Additional message: Response content');

        $message = 'Response content';
        $mockResponse = new MockResponse($message);
        $httpClientMock = new MockHttpClient($mockResponse);

        $decorator = new LoggerResponseWithMessageDecorator($httpClientMock, $loggerMock, 'Additional message: ');

        $response = $decorator->request('GET', 'http://example.com');

        self::assertSame($message, $response->getContent());
    }
}
