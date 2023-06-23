<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils;

use ContainerAP5UoKq\get_Console_Command_About_LazyService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class AbstractFileSystemTestCase extends TestCase
{
    protected ?Filesystem $filesystem = null;
    protected ?string $tempDirectory = null;

    protected function setUp(): void
    {
        $this->filesystem = new Filesystem();
        $this->tempDirectory = sys_get_temp_dir().'/test_tmp_dir';
        $this->filesystem->mkdir($this->tempDirectory);
    }

    protected function tearDown(): void
    {
        $this->filesystem->remove($this->tempDirectory);
    }
}