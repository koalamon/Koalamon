<?php

namespace Koalamon\Integration\MissingRequestBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\System;

class DefaultController extends ProjectAwareController
{
    const INTEGRATION_KEY = 'b312997e-122a-45ac-b25b-f1f2fd8effe4';

    /**
     * @param System $system
     */
    private function getCollections(System $system)
    {
        return $this->getDoctrine()
            ->getRepository('KoalamonIntegrationMissingRequestBundle:Collection')
            ->findBySystem($system);
    }

    public function indexAction()
    {
        $systems = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:System')
            ->findBy(['project' => $this->getProject(), 'parent' => null], ['name' => 'ASC']);

        foreach ($systems as $system) {

            $subSystems = array();

            foreach ($system->getSubsystems() as $subsystem) {
                $subSystems[] = ['system' => $subsystem, 'collections' => $this->getCollections($subsystem)];
            }

            $systemCollections[] = ['system' => $system, 'collections' => $this->getCollections($system), 'subsystems' => $subSystems];
        }

        $collections = $this->getDoctrine()->getRepository('KoalamonIntegrationMissingRequestBundle:Collection')->findBy(['project' => $this->getProject()], ['name' => 'ASC']);

        return $this->render('KoalamonIntegrationMissingRequestBundle:Default:index.html.twig', ['collections' => $collections, 'systemCollections' => $systemCollections]);
    }
}
