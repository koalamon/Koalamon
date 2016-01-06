<?php

namespace Koalamon\WebhookBundle\Formats;

use Bauer\IncidentDashboard\CoreBundle\Entity\Event;
use Bauer\IncidentDashboard\CoreBundle\Entity\RawEvent;
use Symfony\Component\HttpFoundation\Request;

class NewRelicFormat implements Format
{
    const STATUS_SUCCESS = "open";

    public function createEvent(Request $request, $payload)
    {
        $newEvent = json_decode($payload);

        if ($newEvent->current_state == self::STATUS_SUCCESS) {
            $status = Event::STATUS_FAILURE;
        } else {
            $status = Event::STATUS_SUCCESS;
        }

        $event = new RawEvent();

        $event->setStatus($status);
        $event->setIdentifier("newrelic_" . $newEvent->policy_name . "_" . $this->translate($newEvent->targets[0]->name));
        $event->setMessage($newEvent->details);
        $event->setSystem($this->translate($newEvent->targets[0]->name));
        $event->setUrl($newEvent->incident_url);
        $event->setType("newrelic");
        $event->setUnique(true);

        return $event;
    }

    private function translate($name)
    {
        $translation = array("InTouch Production" => "intouch.wunderweib.de",
            "TV-Movie - Production" => "www.tvmovie.de",
            "Cosmopolitan - Production" => "www.cosmopolitan.de",
            "Wunderweib - Production" => "www.wunderweib.de",
            "web: intouch.wunderweib.de" => "intouch.wunderweib.de",
            "lxwebco01" => "www.cosmopolitan.de",
            "BRAVO production" => "www.bravo.de",
            "TVMOVIE production" => "www.tvmovie.de",
            "INTOUCH production" => "intouch.wunderweib.de",
        );

        if (array_key_exists($name, $translation)) {
            return $translation[$name];
        }

        return $name;
    }
}