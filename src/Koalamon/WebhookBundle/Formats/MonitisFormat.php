<?php

namespace Koalamon\WebhookBundle\Formats;

use Bauer\IncidentDashboard\CoreBundle\Entity\Event;
use Bauer\IncidentDashboard\CoreBundle\Entity\RawEvent;
use Symfony\Component\HttpFoundation\Request;

class MonitisFormat implements Format
{
    const STATUS_PROBLEM = "PROBLEM";

    public function createEvent(Request $request, $payload)
    {
        $newEvent = json_decode(urldecode($payload));

        if ($request->get('confirmationURL')) {
            $url = trim(preg_replace('/\s\s+/', ' ', $request->get('confirmationURL')));
            $content = file_get_contents($url);
            return false;
        }

        if (!property_exists($newEvent, 'alert')) {
            return false;
        }

        $alert = $newEvent->alert;

        $event = new RawEvent();

        $event->setType('monitis');

        $system = str_replace('http://', '', $alert->url);
        $system = str_replace(' ', '', $system);

        $event->setSystem($system);

        if ($alert->alertType === self::STATUS_PROBLEM) {
            $event->setStatus(Event::STATUS_FAILURE);
            if (property_exists($alert, "errorString")) {
                $event->setMessage($alert->errorString);
            } else {
                $event->setMessage('Monitis found an error for ' . $alert->name . '.');
            }
        } else {
            $event->setStatus(Event::STATUS_SUCCESS);
            $event->setMessage("");
        }

        $event->setIdentifier("monitis_" . $system . "_" . $alert->type);
        $event->setUnique(false);
        $event->setUrl('http://dashboard.monitis.com/');

        if (property_exists($alert, 'stepDuration')) {
            $event->setValue($alert->stepDuration);
        }

        return $event;
    }
}
