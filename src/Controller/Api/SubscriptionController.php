<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\Subsciption\Command\CommandSubscriptionRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class SubscriptionController
{
    public function __construct(
        private CommandSubscriptionRepositoryInterface $commandRepository,
    ) {
    }

    #[Route('/subscribe', methods: 'POST')]
    public function write(Request $request, ValidatorInterface $validator): JsonResponse
    {
        /** @var string $email */
        $email = $request->request->get('email');

        $errors = $validator->validate($email, new Email());
        if ($errors->count()) {
            return new JsonResponse(
                [
                    'message' => $errors[0]->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $isStored = $this->commandRepository->store($email);

        return new JsonResponse(
            [
                'message' => $isStored ? 'Email was added' : 'Email is already added',
            ],
            $isStored ? Response::HTTP_OK : Response::HTTP_CONFLICT
        );
    }
}
