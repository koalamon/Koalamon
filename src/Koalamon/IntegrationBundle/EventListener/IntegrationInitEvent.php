<?php

namespace Koalamon\IntegrationBundle\EventListener;


use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Koalamon\IntegrationBundle\Integration\IntegrationContainer;
use Symfony\Component\EventDispatcher\Event;

class IntegrationInitEvent extends Event
{
    private $integrationContainer;
    private $project;

    public function __construct(IntegrationContainer $integrationContainer, Project $project)
    {
        $this->integrationContainer = $integrationContainer;
        $this->project = $project;
    }

    public function getIntegrationContainer()
    {
        return $this->integrationContainer;
    }

    public function getProject()
    {
        return $this->project;
    }
}