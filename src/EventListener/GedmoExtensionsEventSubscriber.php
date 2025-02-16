<?php

declare(strict_types=1);

namespace App\EventListener;

use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class GedmoExtensionsEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private TranslatableListener $translatableListener,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['configureTranslatableListener'], // Must run after the locale is configured
            ],
        ];
    }

    /**
     * Configures the translatable listener using the request locale
     */
    public function configureTranslatableListener(RequestEvent $event): void
    {
        $this->translatableListener->setTranslatableLocale($event->getRequest()->getLocale());
    }
}
