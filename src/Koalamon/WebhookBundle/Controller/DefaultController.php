<?php

namespace Koalamon\WebhookBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Entity\Event;

use Bauer\IncidentDashboard\CoreBundle\Entity\EventIdentifier;
use Bauer\IncidentDashboard\CoreBundle\EventListener\NewEventEvent;
use Bauer\IncidentDashboard\CoreBundle\Util\ProjectHelper;
use Koalamon\WebhookBundle\Formats\AppDynamicsFormat;
use Koalamon\WebhookBundle\Formats\DefaultFormat;
use Koalamon\WebhookBundle\Formats\FormatHandler;
use Koalamon\WebhookBundle\Formats\JenkinsFormat;
use Koalamon\WebhookBundle\Formats\JiraFormat;
use Koalamon\WebhookBundle\Formats\MonitisFormat;
use Koalamon\WebhookBundle\Formats\MonitorUsFormat;
use Koalamon\WebhookBundle\Formats\NewRelicFormat;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    const STATUS_SUCCESS = "success";
    const STATUS_FAILURE = "failure";
    const STATUS_SKIPPED = "skipped";

    private function getJsonRespone($status, $message = "")
    {
        return new JsonResponse(array('status' => $status, 'message' => $message));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexAction(Request $request, $formatName = "default")
    {
        $payload = file_get_contents('php://input');

        $content = "queryString: " . $request->getQueryString() . "\n payload: " . $payload;

        file_put_contents("/tmp/koalamon/webhook_" . $formatName . ".log", json_encode($content));

        $project = $this->getProject($request->get("api_key"));

        if ($project == null) {
            return $this->getJsonRespone(self::STATUS_FAILURE, "No project with api_key " . $request->get("api_key") . ' found.');
        }

        $rawEvent = $this->getFormatHandler()->run($formatName, $request, $payload);

        if ($rawEvent === false) {
            return $this->getJsonRespone(self::STATUS_SKIPPED);
        }

        $event = new Event();

        $event->setStatus($rawEvent->getStatus());
        $event->setMessage($rawEvent->getMessage());
        $event->setSystem($rawEvent->getSystem());
        $event->setType($rawEvent->getType());
        $event->setUnique($rawEvent->isUnique());
        $event->setUrl($rawEvent->getUrl());
        $event->setValue($rawEvent->getValue());

        $em = $this->getDoctrine()->getManager();

        $identifier = $em->getRepository('BauerIncidentDashboardCoreBundle:EventIdentifier')
            ->findOneBy(array('project' => $project, 'identifier' => $rawEvent->getIdentifier()));

        if (is_null($identifier)) {
            $identifier = new EventIdentifier();
            $identifier->setProject($project);
            $identifier->setIdentifier($rawEvent->getIdentifier());

            $em->persist($identifier);
            $em->flush();
        }

        $event->setEventIdentifier($identifier);

        $translatedEvent = $this->translate($event);

        ProjectHelper::addEvent($this->get("Router"), $em, $translatedEvent, $this->get('event_dispatcher'));

        /* $eventDispatcher = $this->get('event_dispatcher');
         $dispatcherEvent = new NewEventEvent($translatedEvent);
         $eventDispatcher->dispatch('koalamon.event.create', $dispatcherEvent);*/

        return $this->getJsonRespone(self::STATUS_SUCCESS);
    }

    public function formatAction(Request $request, $format)
    {
        return $this->indexAction($request, $format);
    }

    private function getFormatHandler()
    {
        $formatHandler = new FormatHandler();

        $formatHandler->addFormat("default", new DefaultFormat());
        $formatHandler->addFormat("newrelic", new NewRelicFormat());
        $formatHandler->addFormat("jenkins", new JenkinsFormat());
        $formatHandler->addFormat("jira", new JiraFormat());
        $formatHandler->addFormat("appdynamics", new AppDynamicsFormat());
        $formatHandler->addFormat("monitis", new MonitisFormat());
        $formatHandler->addFormat("monitorus", new MonitorUsFormat());

        return $formatHandler;
    }

    private function translate(Event $event)
    {
        $translations = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:Translation')
            ->findBy(array('project' => $event->getEventIdentifier()->getProject()));

        foreach ($translations as $translation) {
            if (preg_match('^' . $translation->getIdentifier() . '^', $event->getEventIdentifier()->getIdentifier())) {
                return $translation->translate($event);
            }
        }

        return $event;
    }

    private function getProject($apiKey)
    {
        $project = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:Project')
            ->findOneBy(array("apiKey" => $apiKey));

        return $project;
    }
}
