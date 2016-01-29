<?php

namespace Koalamon\IntegrationBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Koalamon\IntegrationBundle\EventListener\IntegrationInitEvent;
use Koalamon\IntegrationBundle\Integration\IntegrationContainer;

class DefaultController extends ProjectAwareController
{
    public function indexAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $dispatcher = $this->get('event_dispatcher');

        $integrationContainer = new IntegrationContainer();

        $dispatcher->dispatch('koalamon.integration.init', new IntegrationInitEvent($integrationContainer, $this->getProject()));

        return $this->render('KoalamonIntegrationBundle:Default:index.html.twig', ['integrationContainer' => $integrationContainer]);
    }
}