<?php

namespace Bauer\IncidentDashboard\StatBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Entity\Event;
use Bauer\IncidentDashboard\CoreBundle\Util\DateHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @param Event $event
     * @return JsonResponse
     */
    public function dayAction(Event $event)
    {
        $events = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:Event')
            ->findByRange($event->getEventIdentifier()->getIdentifier(), new \DateTime("- 1 day"));

        $stats = array(Event::STATUS_SUCCESS => array(), Event::STATUS_FAILURE => array());

        foreach ($events as $event) {
            $hour = $event->getCreated()->format('G');
            if (array_key_exists($hour, $stats[$event->getStatus()])) {
                $stats[$event->getStatus()][(int)$hour] = $stats[$event->getStatus()][(int)$hour] + 1;
            } else {
                $stats[$event->getStatus()][(int)$hour] = 1;
            }
        }

        $data = [['Status', 'Failure', 'Success', ['role' => 'annotation']]];

        $currentHour = date('G');

        for ($i = 24; $i > 0; $i--) {

            $theHour = (($currentHour + 24 - $i) % 24) + 1;
            $amPmHour = DateHelper::toAmPm($theHour);

            if ($theHour == 24) {
                $theHour = 0;
            }

            if (!array_key_exists($theHour, $stats[Event::STATUS_FAILURE])) {
                $stats[Event::STATUS_FAILURE][$theHour] = 0;
            }
            if (!array_key_exists($theHour, $stats[Event::STATUS_SUCCESS])) {
                $stats[Event::STATUS_SUCCESS][$theHour] = 0;
            }

            $data[] = array($amPmHour,
                $stats[Event::STATUS_FAILURE][$theHour],
                $stats[Event::STATUS_SUCCESS][$theHour],
                '');
        }

        return new JsonResponse($data);
    }
}
