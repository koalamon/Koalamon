<?php

namespace Koalamon\DefaultBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\Event;
use Bauer\IncidentDashboard\CoreBundle\Entity\EventIdentifier;
use Bauer\IncidentDashboard\CoreBundle\Entity\KnownIssue;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;

class EventController extends ProjectAwareController
{
    public function markAsKnownIssueAction(EventIdentifier $eventIdentifier)
    {
        $this->setProject($eventIdentifier->getProject());
        $this->assertUserRights(UserRole::ROLE_COLLABORATOR);

        $eventIdentifier->setKnownIssue(true);

        $project = $eventIdentifier->getProject();
        $project->decOpenIncidentCount();

        $em = $this->getDoctrine()->getManager();
        $em->persist($eventIdentifier);
        $em->persist($project);
        $em->flush();

        return $this->redirectToRoute("bauer_incident_dashboard_core_homepage", array('project' => $project->getIdentifier()));
    }

    public function unMarkAsKnownIssueAction(EventIdentifier $eventIdentifier)
    {
        $this->setProject($eventIdentifier->getProject());
        $this->assertUserRights(UserRole::ROLE_COLLABORATOR);

        $eventIdentifier->setKnownIssue(false);
        $project = $eventIdentifier->getProject();
        $project->incOpenIncidentCount();

        $em = $this->getDoctrine()->getManager();
        $em->persist($eventIdentifier);
        $em->persist($project);
        $em->flush();

        return $this->redirectToRoute("bauer_incident_dashboard_core_homepage", array('project' => $project->getIdentifier()));
    }
}
