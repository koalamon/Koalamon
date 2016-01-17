<?php

namespace Koalamon\Integration\KoalaPingBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Koalamon\Integration\KoalaPingBundle\Entity\KoalaPingConfig;
use Koalamon\Integration\KoalaPingBundle\Entity\KoalaPingSystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends ProjectAwareController
{
    // @todo should be done inside the config file
    const API_KEY = '27010d2a-5617-4da2-9f0d-993edf547522';

    public function indexAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $systems = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:System')
            ->findBy(['project' => $this->getProject(), 'parent' => null]);

        $koalamonPingSystems = $this->getDoctrine()
            ->getRepository('KoalamonIntegrationKoalaPingBundle:KoalaPingSystem')
            ->findBy(['project' => $this->getProject()]);

	$koalaPings = array();
        foreach ($koalamonPingSystems as $koalamonPingSystem) {
            $koalaPings[] = $koalamonPingSystem->getSystem()->getId();
        }

        $config = $this->getDoctrine()
            ->getRepository('KoalamonIntegrationKoalaPingBundle:KoalaPingConfig')
            ->findOneBy(['project' => $this->getProject()]);

        if (is_null($config)) {
            $config = new KoalaPingConfig();
            $config->setProject($this->getProject());
            $config->setStatus(KoalaPingConfig::STATUS_SELECTED);
        }

        return $this->render('KoalamonIntegrationKoalaPingBundle:Default:index.html.twig', ['systems' => $systems, 'koalaPings' => $koalaPings, 'config' => $config]);
    }

    public function storeAction(Request $request)
    {

        $systems = array_keys((array)$request->get('systems'));
        $status = $request->get('status');

        $em = $this->getDoctrine()->getManager();

        $config = $this->getDoctrine()
            ->getRepository('KoalamonIntegrationKoalaPingBundle:KoalaPingConfig')
            ->findOneBy(['project' => $this->getProject()]);

        if (is_null($config)) {
            $config = new KoalaPingConfig();
            $config->setProject($this->getProject());
        }

        $config->setStatus($status);

        $em->persist($config);

        $koalamonPingSystems = $this->getDoctrine()
            ->getRepository('KoalamonIntegrationKoalaPingBundle:KoalaPingSystem')
            ->findBy(['project' => $this->getProject()]);

        foreach ($koalamonPingSystems as $koalamonPingSystem) {
            $em->remove($koalamonPingSystem);
        }

        $em->flush();

        foreach ($systems as $system) {
            $koalamonSystem = $this->getDoctrine()
                ->getRepository('BauerIncidentDashboardCoreBundle:System')
                ->find($system);

            if ($koalamonSystem->getProject() != $this->getProject()) {
                return new JsonResponse(['status' => 'failure', 'message' => 'You tried to store a system from another project.']);
            }

            $koalaPingSystem = new KoalaPingSystem();
            $koalaPingSystem->setSystem($koalamonSystem);

            $em->persist($koalaPingSystem);
        }

        $em->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'Systems stored.']);
    }
}
