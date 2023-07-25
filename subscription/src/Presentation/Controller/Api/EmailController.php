<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Api;

use App\Application\Client\Currency\CurrencyClientInterface;
use App\Application\Mail\Factory\PlainTextEmailMessageFactory;
use App\Application\Mail\Sender\MailSenderInterface;
use App\Domain\Repository\Subscription\Query\SubscriptionQueryRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class EmailController
{
    public function __construct(
        private CurrencyClientInterface $rateClient,
        private MailSenderInterface $mailSender,
        private PlainTextEmailMessageFactory $emailMessageFactory,
    ) {
    }

    #[Route('/sendEmails', methods: 'POST')]
    public function sendRates(SubscriptionQueryRepositoryInterface $queryRepository): JsonResponse
    {
        $rate = $this->rateClient->getRate();
        $subscribers = $queryRepository->getAll();

        if (empty($subscribers)) {
            return new JsonResponse(
                [
                    'message' => 'Emails were not found!',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $message = $this->emailMessageFactory->create(
            $subscribers,
            'Dear subscriber, the current Bitcoin exchange rate in Hryvnia (UAH) is '.$rate,
        );
        $this->mailSender->send($message);

        return new JsonResponse(
            [
                'message' => 'Message was sent!',
            ]
        );
    }
}