<?php
declare(strict_types=1);

namespace Lib\HttpFoundation;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Serializer;

final class ResponseJsonListener implements EventSubscriberInterface
{
    /**
     * @var Serializer
     */
    private Serializer $serializer;

    /**
     * ResponseJsonListener constructor.
     *
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onKernelView', 30],
        ];
    }

    public function onKernelView(ViewEvent $event): void
    {
        $data = $event->getControllerResult();
        switch (true) {
            case $data instanceof Success:
                $event->setResponse(JsonResponse::fromJsonString($this->serializer->serialize($data->data, 'json')));
                break;
            case $data instanceof Fail:
                $event->setResponse(JsonResponse::fromJsonString($this->serializer->serialize($data->errors, 'json')), 422);
                break;
        }
    }
}
