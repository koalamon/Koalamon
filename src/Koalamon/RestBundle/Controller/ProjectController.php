<?php

namespace Koalamon\RestBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProjectController extends ProjectAwareController
{

    public function systemsAction(Request $request)
    {
        $this->assertApiKey($request->get("api_key"));

        $projectSystems = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:System')
            ->findBy(['project' => $this->getProject(), 'parent' => null]);

        $systems = array();
        foreach ($projectSystems as $system) {
            $systems[] = $system;
        }

        return new JsonResponse($systems);
    }

    public function lastStatusChangeAction()
    {
        return new JsonResponse(array('created' => $this->getProject()->getLastStatusChange()->getTimestamp()));
    }

    public function eventsAction(Request $request)
    {
        header('Access-Control-Allow-Origin: *');

        $this->assertApiKey($request->get("api_key"));
        $project = $this->getProject();

        $eventIdentifiers = $project->getEventIdentifiers();

        $hideKnownIssues = true;

        $events = array();

        foreach ($eventIdentifiers as $eventIdentifier) {
            $lastEvent = $eventIdentifier->getLastEvent();
            if ($lastEvent->getStatus() == $request->get("status")) {
                if ($hideKnownIssues) {
                    if (!$eventIdentifier->isKnownIssue()) {
                        $events[] = $lastEvent;
                    }
                } else {
                    $events[] = $lastEvent;
                }
            }
        }

        return new JsonResponse($events);
    }
}
