<?php

namespace Bauer\IncidentDashboard\CoreBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;

class NewEventEvent extends Event
{
    private $event;
    private $lastEvent;

    /**
     * NewEventEvent constructor.
     */
    public function __construct(\Bauer\IncidentDashboard\CoreBundle\Entity\Event $event,
                                \Bauer\IncidentDashboard\CoreBundle\Entity\Event $lastEvent)
    {
        $this->event = $event;
        $this->lastEvent = $lastEvent;
    }

    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return \Bauer\IncidentDashboard\CoreBundle\Entity\Event
     */
    public function getLastEvent()
    {
        return $this->lastEvent;
    }
}