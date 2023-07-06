<?php

declare(strict_types=1);

namespace App\Tests\Functional\Presentation\Controller\Api;

use App\Tests\Functional\AbstractApiTestCase;
use App\Utils\FileSystem\Writer\FileSystemWriterInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionControllerTest extends AbstractApiTestCase
{
    protected const DEFAULT_HEADERS = [
        'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
    ];

    public function tearDown(): void
    {
        $dir = self::$kernel->getProjectDir();
        $filesystem = new Filesystem();
        $filesystem->remove($dir.'/test_system/emails.txt');
    }

    public function testExpects200(): void
    {
        $result = self::httpPost('/api/subscribe', ['email' => 'expects200@mail.com']);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSame(['message' => 'Email was added'], json_decode($result, true));
    }

    public function testExpects400(): void
    {
        self::httpPost('/api/subscribe', ['email' => 'not_correct_email']);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testExpects409(): void
    {
        $email = 'expects409@mail.com';
        $this->addEmailsToFile([$email]);

        $result = self::httpPost('/api/subscribe', ['email' => $email]);

        self::assertResponseStatusCodeSame(Response::HTTP_CONFLICT);
        self::assertSame(['message' => 'Email is already added'], json_decode($result, true));
    }

    private function addEmailsToFile(array $emails): void
    {
        $fileWriter = self::getContainer()->get(FileSystemWriterInterface::class);
        $fileWriter->writeTo('emails.txt', implode(',', $emails));
    }
}
