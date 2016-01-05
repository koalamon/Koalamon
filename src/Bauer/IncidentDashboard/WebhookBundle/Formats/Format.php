<?php

namespace Bauer\IncidentDashboard\WebhookBundle\Formats;

use Symfony\Component\HttpFoundation\Request;
use Bauer\IncidentDashboard\CoreBundle\Entity\RawEvent;

interface Format
{
    /**
     * Returns an Event object created from the request
     *
     * @param Request $request
     * @param string $payload
     * @return RawEvent
     */
    public function createEvent(Request $request, $payload);
}