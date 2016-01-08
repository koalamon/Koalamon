<?php

namespace Koalamon\DefaultBundle\EventListener;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Koalamon\DefaultBundle\Menu\Menu;
use Symfony\Component\EventDispatcher\Event;

class AdminMenuEvent extends Event
{
    private $menu;
    private $project;

    /**
     * NewEventEvent constructor.
     */
    public function __construct(Menu &$menu, Project $project)
    {
        $this->menu = $menu;
        $this->project = $project;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }
}