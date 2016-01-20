<?php

namespace Koalamon\Integration\MissingRequestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RestController extends Controller
{
    public function getConfigAction(Request $request)
    {
        if ($request->get('integration_key') != DefaultController::INTEGRATION_KEY) {
            return new JsonResponse('You are not allowed to call this action.');
        }

        $collections = $this->getDoctrine()
            ->getRepository('KoalamonIntegrationMissingRequestBundle:Collection')
            ->findBy([], ['name' => 'ASC']);

        $systemCollections = array();

        foreach ($collections as $collection) {
            foreach ($collection->getSystems() as $system) {
                if (!array_key_exists($system->getId(), $systemCollections)) {
                    $systemCollections[$system->getId()]['system'] = $system;
                }
                $systemCollections[$system->getId()]['collections'][] = $collection;
            }
        }

        return new JsonResponse($systemCollections);
    }
}
