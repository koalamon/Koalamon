<?php

namespace Koalamon\WebhookBundle\Formats;

use Bauer\IncidentDashboard\CoreBundle\Entity\Event;
use Bauer\IncidentDashboard\CoreBundle\Entity\RawEvent;
use Symfony\Component\HttpFoundation\Request;

class AppDynamicsFormat implements Format
{
    private $successStatus = array(
        "POLICY_CLOSE_CRITICAL",
        "POLICY_CLOSE_WARNING",
        "POLICY_CANCELED_CRITICAL",
        "POLICY_CANCELED_WARNING");

    public function createEvent(Request $request, $payload)
    {
        // @todo not sure if the clean process is needed anymore
        $cleanPayload = preg_replace("^\${(.*)}^", '""', $payload);

        $newEvent = json_decode($cleanPayload);

        $event = new RawEvent();

        $identifier = "appDynamics_" . $newEvent->event->application->id. '_';
        // appD . application . healthrule
        if ($newEvent->event->healthRuleEvent == "true") {
            $identifier .= $newEvent->event->healthRule->id;
            $event->setUnique(false);
        } else {
            $identifier .= $newEvent->event->incident->id;
            $event->setUnique(true);
        }

        $event->setIdentifier($identifier);
        $event->setMessage($newEvent->event->summaryMessage);

        if (in_array($newEvent->event->eventType, $this->successStatus)) {
            $event->setStatus(Event::STATUS_SUCCESS);
        } else {
            $event->setStatus(Event::STATUS_FAILURE);
        }

        $event->setSystem($this->translate($newEvent->event->application->name));
        $event->setType("AppDynamics");

        $event->setUrl($newEvent->event->deepLink);

        return $event;
    }

    private function translate($name)
    {
        $translation = array(
            "InTouch Production" => "intouch.wunderweib.de",
            "TV-Movie - Production" => "www.tvmovie.de",
            "Cosmopolitan - Production" => "www.cosmopolitan.de",
            "Wunderweib - Production" => "www.wunderweib.de",
            "lxwebco01" => "www.cosmopolitan.de",
            "COSMOPOLITAN production" => "www.cosmopolitan.de",
            "BRAVO production" => "www.bravo.de",
            "TVMOVIE production" => "www.tvmovie.de",
            "INTOUCH production" => "intouch.wunderweib.de",
            "Wunderweib-FS5 PRD" => "www.wunderweib.de"
        );

        if (array_key_exists($name, $translation)) {
            return $translation[$name];
        }

        return $name;
    }
}