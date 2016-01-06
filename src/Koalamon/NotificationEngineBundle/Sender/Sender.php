<?php

namespace Koalamon\NotificationEngineBundle\Sender;

use Bauer\IncidentDashboard\CoreBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Routing\Router;


interface Sender
{
    /*
     * return Option[]
     */
    public function getOptions();

    public function send(Event $event);

    public function init(Router $router, array $initOptions);
}