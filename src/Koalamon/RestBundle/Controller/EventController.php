<?php

namespace Koalamon\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventController extends Controller
{
    public function lastRunAction($project, $identifier)
    {
        $project = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:Project')
            ->findOneBy(array("identifier" => $project));

        $event = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:Event')
            ->findOneBy(array("identifier" => $identifier, "project" => $project), array("created" => "DESC"));

        if (is_null($event)) {
            throw $this->createNotFoundException('No events found for this identifier.');
        }

        return new JsonResponse(array("identifier" => $identifier, "lastrun" => $event->getCreated()->getTimestamp()));
    }
}
