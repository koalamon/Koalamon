<?php

namespace Bauer\IncidentDashboard\CoreBundle\Util;

use Bauer\IncidentDashboard\CoreBundle\Entity\Event;
use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Bauer\IncidentDashboard\CoreBundle\Entity\Tool;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Koalamon\NotificationEngineBundle\Entity\NotificationConfiguration;
use Koalamon\NotificationEngineBundle\Sender\SenderFactory;
use Koalamon\NotificationEngineBundle\Sender\SlackSender;

/**
 * Class ProjectHelper
 *
 * @todo should be handled via dependency injection
 *
 * @package Bauer\IncidentDashboard\CoreBundle\Util
 */
class ProjectHelper
{
    static public function addEvent(Router $router, EntityManager $doctrineManager, Event $event)
    {
        self::handleTool($event, $doctrineManager);

        $project = $event->getEventIdentifier()->getProject();
        // $project->incEventCount();

        $lastEvent = $doctrineManager
            ->getRepository('BauerIncidentDashboardCoreBundle:Event')
            ->findOneBy(array("eventIdentifier" => $event->getEventIdentifier()), array("created" => "DESC"));
        /** @var Event $lastEvent */

        // is status change?
        if (is_null($lastEvent) || $lastEvent->getStatus() != $event->getStatus()) {
            $event->setIsStatusChange(true);

            if ($event->getStatus() == Event::STATUS_SUCCESS) {
                if (!is_null($lastEvent) && !$event->getEventIdentifier()->isKnownIssue()) {
                    $project->decOpenIncidentCount();
                }

                if (is_null($event->getEventIdentifier()->getLastEvent())) {
                    $occurrenceLastEvent = $event->getCreated();
                } else {
                    $occurrenceLastEvent = $event->getEventIdentifier()->getLastEvent()->getLastStatusChange();
                }
                $occurrenceCurrentEvent = $event->getCreated();

                $timeToRecover = abs(($occurrenceCurrentEvent->getTimestamp() - $occurrenceLastEvent->getTimestamp()) / 60);
                $event->getEventIdentifier()->addNewFailure($timeToRecover);
            } else {
                $project->incOpenIncidentCount();
            }

            $project->setLastStatusChange($event->getCreated());
            $event->setLastStatusChange($event->getCreated());
        } else {
            $event->setLastStatusChange($lastEvent->getLastStatusChange());
        }

        $event->getEventIdentifier()->setLastEvent($event);

        $event->getEventIdentifier()->incEventCount();
        if ($event->getStatus() == Event::STATUS_FAILURE) {
            $event->getEventIdentifier()->incFailedEventCount();
        }

        self::storeData($doctrineManager, $event, $project);

        if ((!$lastEvent && $event->getStatus() == Event::STATUS_FAILURE) || ($lastEvent && ($lastEvent->getStatus() != $event->getStatus()))) {
            self::notify($router, $doctrineManager, $event);
        }
    }

    static private function handleTool(Event &$event, EntityManager $doctrineManager)
    {
        $toolName = $event->getType();

        $tool = $doctrineManager->getRepository('BauerIncidentDashboardCoreBundle:Tool')
            ->findOneBy(array('project' => $event->getEventIdentifier()->getProject(), 'identifier' => $toolName));

        if (is_null($tool)) {
            $tool = new Tool();
            $tool->setProject($event->getEventIdentifier()->getProject());
            $tool->setIdentifier($toolName);
            $tool->setActive(false);

            $doctrineManager->persist($tool);
            $doctrineManager->flush();
        }

        $event->getEventIdentifier()->setTool($tool);
    }

    static private function storeData(EntityManager $doctrineManager, Event $event, Project $project)
    {
        $doctrineManager->persist($event);
        $doctrineManager->persist($project);
        $doctrineManager->flush();

        $doctrineManager->persist($event->getEventIdentifier());
        $doctrineManager->flush();
    }

    static private function notify(Router $router, EntityManager $doctrineManager, Event $event)
    {
        $configs = $doctrineManager->getRepository('KoalamonNotificationEngineBundle:NotificationConfiguration')
            ->findBy(['project' => $event->getEventIdentifier()->getProject()]);

        /** @var NotificationConfiguration[] $configs */

        foreach ($configs as $config) {
            if ($config->isNotifyAll() || $config->isConnectedTool($event->getEventIdentifier()->getTool())) {
                $sender = SenderFactory::getSender($config->getSenderType());
                $sender->init($router, $config->getOptions());

                $sender->send($event);
            }
        }
    }
}
