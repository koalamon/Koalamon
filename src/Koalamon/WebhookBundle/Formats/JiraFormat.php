<?php

namespace Koalamon\WebhookBundle\Formats;

use Bauer\IncidentDashboard\CoreBundle\Entity\Event;
use Bauer\IncidentDashboard\CoreBundle\Entity\RawEvent;
use Symfony\Component\HttpFoundation\Request;

class JiraFormat implements Format
{
    private $successStates = array('Closed', 'Resolved');

    public function createEvent(Request $request, $payload)
    {
        $newEvent = json_decode($payload);

        $issue = $newEvent->issue;

        $message = $issue->fields->summary;
        $project = $issue->fields->project->key;
        $jiraStatus = $issue->fields->status->name;

        $identifier = "jira_" . $issue->key;

        if (in_array($jiraStatus, $this->successStates)) {
            $status = Event::STATUS_SUCCESS;
        } else {
            $status = Event::STATUS_FAILURE;
        }

        $event = new RawEvent();

        $event->setIdentifier($identifier);
        $event->setMessage($message);
        $event->setSystem($project);
        $event->setStatus($status);

        $parts = parse_url($issue->self);
        $url = $parts["scheme"] . "://" . $parts["host"] . "/browse/" . $newEvent->issue->key;
        $event->setUrl($url);

        $event->setUnique(true);
        $event->setType("jira");

        return $event;
    }
}
