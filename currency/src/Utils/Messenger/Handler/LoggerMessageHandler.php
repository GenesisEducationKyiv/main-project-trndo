<?php

declare(strict_types=1);

namespace App\Utils\Messenger\Handler;

use App\Message\LoggerMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LoggerMessageHandler
{
    //*display logs in stdout according to task
    public function __invoke(LoggerMessage $message): void
    {
        $string = 'From Handler: '.$message->getChannel().': Message: '.$message->getMessage()
            .'. Context: '.json_encode($message->getContext()).PHP_EOL;

        echo $string;
    }
}