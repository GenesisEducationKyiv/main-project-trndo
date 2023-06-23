<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractKernelBrowserTestCase extends WebTestCase
{
    protected static ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        if (!self::$booted) {
            self::$client = self::createClient();
        }
    }
}