<?php

namespace Koalamon\WebhookBundle\Formats;

use Bauer\IncidentDashboard\CoreBundle\Entity\RawEvent;
use Symfony\Component\HttpFoundation\Request;

class DefaultFormat implements Format
{
    public function createEvent(Request $request, $payload)
    {
        $newEvent = json_decode($payload);

        $event = new RawEvent();

        $event->setIdentifier($newEvent->identifier);
        $event->setMessage($newEvent->message);
        $event->setStatus($newEvent->status);
        $event->setSystem($newEvent->system);

        if (property_exists($newEvent, "value")) {
            $event->setValue($newEvent->value);
        }

        if (property_exists($newEvent, "url")) {
            $event->setUrl($newEvent->url);
        } else {
            $event->setUrl("");
        }

        if (property_exists($newEvent, "type")) {
            $event->setType($newEvent->type);
        }

        return $event;
    }
}