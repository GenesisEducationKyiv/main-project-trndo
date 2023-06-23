<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class AbstractFileSystemKernelTestCase extends KernelTestCase
{
    public function tearDown(): void
    {
        $dir = self::bootKernel()->getProjectDir();
        $filesystem = new Filesystem();
        $filesystem->remove($dir.'/test_system/emails.txt');
    }
}