<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Api;

use App\Repository\Subsciption\Query\QuerySubscriptionRepositoryInterface;
use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\CurrencyRateComparator\CurrencyRateComparatorChainInterface;
use App\Utils\Mail\Factory\PlainTextEmailMessageFactory;
use App\Utils\Mail\Sender\MailSenderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CurrencyRateEmailController
{
    public function __construct(
        private CurrencyRateComparatorChainInterface $rateComparator,
        private MailSenderInterface $mailSender,
        private PlainTextEmailMessageFactory $emailMessageFactory,
    ) {
    }

    #[Route('/sendEmails', methods: 'POST')]
    public function sendRates(QuerySubscriptionRepositoryInterface $queryRepository): JsonResponse
    {
        $rate = $this->rateComparator->compare(Currency::BTC, Currency::UAH);
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
