<?php

namespace App\EventListener;

use App\Exception\BusinessException;
use App\Traits\ResponseStatusTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ExceptionListener
{
    use ResponseStatusTrait;

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof MethodNotAllowedHttpException) {

            $response = new Response();

            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            $event->setResponse($response);
        }

        if ($exception instanceof UnprocessableEntityHttpException) {

            $event->allowCustomResponseCode();

            $response = $this->failed(['errors' => [$exception->getMessage()]]);

            $event->setResponse($response);

            $event->stopPropagation();
        }

        /** @var BusinessException $exception */
        if ($exception instanceof BusinessException) {

            $event->allowCustomResponseCode();

            $errors = $exception->getErrors();

            $response = $this->failed(['message' => $exception->getMessage() ,'errors' => $errors]);

            $event->setResponse($response);

            $event->stopPropagation();
        }
    }
}