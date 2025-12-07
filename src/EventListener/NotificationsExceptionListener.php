<?php

namespace App\EventListener;

use App\Service\Exception\UserNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class NotificationsExceptionListener
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    #[AsEventListener]
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        if (!str_starts_with($event->getRequest()->getPathInfo(), "/notifications")) {
            return;
        }

        // Setting custom JSON messages for different exceptions
        $exceptionClass = $event->getThrowable();
        $error = match ($exceptionClass::class) {
            UserNotFoundException::class => $this->getResponse($exceptionClass->getMessage(), Response::HTTP_NOT_FOUND),
            default => $this->getResponse('Unexpected error', Response::HTTP_INTERNAL_SERVER_ERROR),
        };

        // only log internet server errors
        if ($error->getStatusCode() == Response::HTTP_INTERNAL_SERVER_ERROR) {
            $this->logger->error($exceptionClass->getMessage(), ['trace' => $exceptionClass->getTraceAsString()]);
        }

        $event->setResponse($error);
    }

    private function getResponse(string $message, int $statusCode): JsonResponse
    {
        return new JsonResponse(['error' => $message], $statusCode);
    }
}
