<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Api;

use App\Tests\Functional\AbstractApiTestCase;
use App\Utils\FileSystem\Writer\FileSystemWriterInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

class CurrencyRateEmailControllerTest extends AbstractApiTestCase
{
    private const EMAILS = ['email1web@example.com', 'email2web@example.com', 'email3web@example.com'];

    public function tearDown(): void
    {
        $dir = self::$kernel->getProjectDir();
        $filesystem = new Filesystem();
        $filesystem->remove($dir.'/test_system/emails.txt');
    }

    public function testExpects404(): void
    {
        $result = self::httpPost('/api/sendEmails', ['emai']);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertSame(['message' => 'Emails were not found!'], json_decode($result, true));
    }

    public function testExpects200(): void
    {
        $this->loadEmailsToFile();

        $result = self::httpPost('/api/sendEmails', []);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSame(['message' => 'Message was sent!'], json_decode($result, true));
    }

    private function loadEmailsToFile(): void
    {
        $fileWriter = self::getContainer()->get(FileSystemWriterInterface::class);
        $fileWriter->writeTo('emails.txt', implode(',', self::EMAILS));
    }
}
