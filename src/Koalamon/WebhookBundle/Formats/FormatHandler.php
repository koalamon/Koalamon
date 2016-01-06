<?php

namespace Koalamon\WebhookBundle\Formats;

use Symfony\Component\HttpFoundation\Request;
use Bauer\IncidentDashboard\CoreBundle\Entity\RawEvent;

class FormatHandler
{
    /**
     * @var Format[]
     */
    private $formats = array();

    public function addFormat($key, Format $format)
    {
        $this->formats[$key] = $format;
    }

    /**
     * @param $formatName
     * @param Request $request
     * @param $payload
     * @return RawEvent
     */
    public function run($formatName, Request $request, $payload)
    {
        if(!array_key_exists($formatName, $this->formats)) {
            throw new \RuntimeException("Format (".$formatName.") not found");
        }
        return $this->formats[$formatName]->createEvent($request, $payload);
    }
}