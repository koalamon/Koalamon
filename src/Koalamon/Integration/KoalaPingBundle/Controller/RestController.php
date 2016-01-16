<?php

namespace Koalamon\Integration\KoalaPingBundle\Controller;

use Koalamon\Integration\KoalaPingBundle\Entity\KoalaPingConfig;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RestController extends Controller
{
    // @todo should be done inside the config file
    const INTEGRATION_KEY = '27010d2a-5617-4da2-9f0d-993edf547522';

    public function systemsAction(Request $request)
    {
        if ($request->get('integration_key') != self::INTEGRATION_KEY) {
            return new JsonResponse(['status' => "failure", 'message' => 'Integration key invalid.']);
        }

        $configs = $this->getDoctrine()
            ->getRepository('KoalamonIntegrationKoalaPingBundle:KoalaPingConfig')
            ->findBy(['status' => KoalaPingConfig::STATUS_ALL]);

        $pingableSystems = array();

        foreach ($configs as $config) {
            $systems = $config->getProject()->getSystems();
            $pingableSystems = array_merge($pingableSystems, $systems->toArray());
        }

        $configs = $this->getDoctrine()
            ->getRepository('KoalamonIntegrationKoalaPingBundle:KoalaPingConfig')
            ->findBy(['status' => KoalaPingConfig::STATUS_SELECTED]);

        foreach ($configs as $config) {
            $koalamonPingSystems = $this->getDoctrine()
                ->getRepository('KoalamonIntegrationKoalaPingBundle:KoalaPingSystem')
                ->findBy(['project' => $config->getProject()]);

            $selectedSystems = array();

            foreach ($koalamonPingSystems as $koalamonPingSystem) {
                $pingableSystems[] = $koalamonPingSystem->getSystem();
            }

            // $pingableSystems[] = ['systems' => $selectedSystems, 'project' => ['api_key' => $config->getProject()->getApiKey()]];
        }

        return new JsonResponse($pingableSystems);
    }

}
