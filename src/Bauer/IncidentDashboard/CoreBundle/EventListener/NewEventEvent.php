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
    public function __construct(\Bauer\IncidentDashboard\CoreBundle\Entity\Event $event)
    {
        $this->event = $event;
    }

    public function setLastEvent(\Bauer\IncidentDashboard\CoreBundle\Entity\Event $event)
    {
        $this->lastEvent = $event;
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

    public function hasLastEvent()
    {
        return !is_null($this->lastEvent);
    }
}