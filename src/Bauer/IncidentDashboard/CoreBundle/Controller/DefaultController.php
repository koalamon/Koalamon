<?php

namespace Bauer\IncidentDashboard\CoreBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Entity\Event;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Bauer\IncidentDashboard\CoreBundle\Util\ProjectHelper;

class DefaultController extends ProjectAwareController
{
    public function closeAction(Event $event)
    {
        $this->assertUserRights(UserRole::ROLE_COLLABORATOR);

        $closeEvent = new Event();

        $closeEvent->setEventIdentifier($event->getEventIdentifier());
        $closeEvent->setSystem($event->getSystem());
        $closeEvent->setStatus(Event::STATUS_SUCCESS);
        $closeEvent->setIsStatusChange(true);
        $closeEvent->setUnique($event->isUnique());
        $closeEvent->setType($event->getType());

        $closeEvent->setMessage('Manually closed by ' . $this->getUser()->getUsername() . '.');

        ProjectHelper::addEvent($this->get("Router"), $this->getDoctrine()->getEntityManager(), $closeEvent);

        return $this->redirect($this->generateUrl("bauer_incident_dashboard_core_homepage", array("project" => $event->getEventIdentifier()->getProject()->getIdentifier())));
    }

    public function statusAction($dashboard = false)
    {
        $this->assertUserRights(UserRole::ROLE_WATCHER);

        $eventIdentifiers = $this->getDoctrine()->getRepository('BauerIncidentDashboardCoreBundle:EventIdentifier')->findNewest($this->getProject());

        if ($dashboard) {
            $baseTemplate = '::dashboard.html.twig';
        } else {
            $baseTemplate = '::home.html.twig';
        }

        return $this->render('BauerIncidentDashboardCoreBundle:Default:status.html.twig',
            array('listCreated' => time(), 'systems' => $this->getProject()->getSystems(), 'eventIndentifiers' => $eventIdentifiers, 'baseTemplate' => $baseTemplate, "dashboard" => $dashboard));
    }

    public function dashboardAction()
    {
        return $this->statusAction(true);
    }

    public function showPastAction($intervalInHours)
    {
        $this->assertUserRights(UserRole::ROLE_WATCHER);

        $events = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:Event')
            ->findFailuresSince(new \DateInterval("PT" . $intervalInHours . "H"), $this->getProject());

        return $this->render('BauerIncidentDashboardCoreBundle:Default:24h.html.twig',
            array('events' => $events, 'hours' => $intervalInHours));
    }

    public function toolsAction()
    {
        $this->assertUserRights(UserRole::ROLE_WATCHER);

        $tools = $this->getDoctrine()->getRepository('BauerIncidentDashboardCoreBundle:Tool')->findBy(array("project" => $this->getProject(), "active" => true));

        return $this->render('BauerIncidentDashboardCoreBundle:Default:tools.html.twig', array('tools' => $tools));
    }

    public function systemsAction()
    {
        $this->assertUserRights(UserRole::ROLE_WATCHER);

        $systems = $this->getProject()->getSystems();

        $tools = $this->getProject()->getTools();

        $eventIdentifiers = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:EventIdentifier')
            ->findNewest($this->getProject());

        $systemInfos = array();

        // prefill system info array
        foreach ($systems as $system) {
            $systemInfos[$system->getIdentifier()]["system"] = $system;

            foreach ($tools as $tool) {
                if ($tool->isAlwaysActive()) {
                    $status = Event::STATUS_SUCCESS;
                } elseif ($tool->isSystemSpecific()) {
                    $status = "none";
                } else {
                    $status = "n/a";
                }
                $systemInfos[$system->getIdentifier()]["tools"][$tool->getIdentifier()] = array("status" => $status, "tool" => $tool);
            }
        }

        // set status if the system/tool combination was found
        foreach ($eventIdentifiers as $eventIdentifier) {
            if (array_key_exists($eventIdentifier->getLastEvent()->getSystem(), $systemInfos)) {
                if (array_key_exists($eventIdentifier->getLastEvent()->getType(), $systemInfos[$eventIdentifier->getLastEvent()->getSystem()]["tools"])) {
                    $systemInfos[$eventIdentifier->getLastEvent()->getSystem()]["tools"][$eventIdentifier->getLastEvent()->getType()]["status"] = $eventIdentifier->getLastEvent()->getStatus();
                }
            }
        }

        return $this->render('BauerIncidentDashboardCoreBundle:Default:systems.html.twig',
            array('systemInfos' => $systemInfos, 'dashboard' => false));
    }
}
