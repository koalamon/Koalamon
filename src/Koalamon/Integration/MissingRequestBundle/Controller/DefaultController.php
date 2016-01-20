<?php

namespace Koalamon\Integration\MissingRequestBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;

class DefaultController extends ProjectAwareController
{
    const INTEGRATION_KEY = 'b312997e-122a-45ac-b25b-f1f2fd8effe4';

    public function indexAction()
    {
        $systems = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:System')
            ->findBy(['project' => $this->getProject(), 'parent' => null]);

        foreach ($systems as $system) {
            $collections = $this->getDoctrine()
                ->getRepository('KoalamonIntegrationMissingRequestBundle:Collection')
                ->findBySystem($system);

            $systemCollections[] = ['system' => $system, 'collections' => $collections];
        }

        $collections = $this->getDoctrine()->getRepository('KoalamonIntegrationMissingRequestBundle:Collection')->findBy(['project' => $this->getProject()], ['name' => 'ASC']);
        return $this->render('KoalamonIntegrationMissingRequestBundle:Default:index.html.twig', ['collections' => $collections, 'systemCollections' => $systemCollections]);
    }
}
