<?php

declare(strict_types=1);

namespace App\Presentation\Command;

use App\Domain\Message\LoggerMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\TransportInterface;

#[AsCommand(name: 'app:get-logs')]
class LogFromMessengerReaderCommand extends Command
{
    private bool $stop = false;

    public function __construct(private TransportInterface $transport, protected string $name = 'app:get-logs')
    {
        parent::__construct($this->name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while ( ! $this->stop) {
            /** @var \Generator $message */
            $message = $this->transport->get();

            if ( ! $message->valid()) {
                $this->stop = false;
                break;
            }

            /** @var Envelope $messageEnvelope */
            $messageEnvelope = $message->current();

            /** @var LoggerMessage $loggerMessage */
            $loggerMessage = $messageEnvelope->getMessage();
            $this->log($loggerMessage, $output);

            $this->transport->ack($messageEnvelope);
        }

        return Command::SUCCESS;
    }

    private function log(LoggerMessage $loggerMessage, OutputInterface $output): void
    {
        $output->write(
            $loggerMessage->getChannel().': Message: '.$loggerMessage->getMessage()
            .'. Context: '.json_encode($loggerMessage->getContext()).PHP_EOL
        );
    }
}
