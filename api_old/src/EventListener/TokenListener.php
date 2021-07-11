<?php

namespace App\EventListener;

use App\Controller\TokenAuthenticatedController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TokenListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', 10],
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        if (in_array($_SERVER['APP_ENV'], ['dev', 'test']) && 'Test' === $event->getRequest()->headers->get('User-Agent')) {
            return;
        }

        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof TokenAuthenticatedController) {
            $token = explode(' ', $event->getRequest()->headers->get('Authorization'))[1] ?? null;

            try {
                $client = new Client();
                $client->post('http://authentication:8080/api/authentication/authorize', [
                    RequestOptions::HEADERS => [
                        'Authorization' => sprintf('Bearer %s', $token),
                    ],
                ]);
            } catch (ClientException $exception) {
                throw new AccessDeniedException('This action needs a valid token!');
            }
        }
    }
}