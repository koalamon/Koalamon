<?php

namespace Bauer\IncidentDashboard\CoreBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bauer\IncidentDashboard\CoreBundle\Entity\EventIdentifierRepository")
 */
class EventIdentifier
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=255)
     */
    private $identifier;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $knownIssue = false;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $eventCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $failedEventCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $failureCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $meanTimeToRecover = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Tool", inversedBy="eventIdentifier")
     * @ORM\JoinColumn(name="tool_id", referencedColumnName="id", nullable=true)
     **/
    private $tool;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="eventIdentifiers")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     **/
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="none")
     * @ORM\JoinColumn(name="last_event_id", referencedColumnName="id")
     **/
    private $lastEvent;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="eventIdentifier")
     */
    private $events;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     *
     * @return Event
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return Event
     */
    public function getLastEvent()
    {
        return $this->lastEvent;
    }

    /**
     * @param Event $lastEvent
     */
    public function setLastEvent(Event $lastEvent)
    {
        $this->lastEvent = $lastEvent;
    }

    /**
     * @return boolean
     */
    public function isKnownIssue()
    {
        return $this->knownIssue;
    }

    /**
     * @param boolean $knownIssue
     */
    public function setKnownIssue($knownIssue)
    {
        $this->knownIssue = $knownIssue;
    }

    /**
     * @return Event[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    public function incEventCount()
    {
        $this->eventCount++;
    }

    /**
     * @return int
     */
    public function getEventCount()
    {
        return $this->eventCount;
    }

    /**
     * @return int
     */
    public function getFailedEventCount()
    {
        return $this->failedEventCount;
    }

    /**
     * @param int $failedEventCount
     */
    public function incFailedEventCount()
    {
        ++$this->failedEventCount;
    }

    /**
     * @return mixed
     */
    public function getTool()
    {
        return $this->tool;
    }

    /**
     * @param mixed $tool
     */
    public function setTool($tool)
    {
        $this->tool = $tool;
    }

    /**
     * @return int
     */
    public function getFailureCount()
    {
        return $this->failureCount;
    }

    /**
     * @param int $failureCount
     */
    private function incFailureCount()
    {
        ++$this->failureCount;
    }

    /**
     * @return int
     */
    public function getMeanTimeToRecover()
    {
        return $this->meanTimeToRecover;
    }

    private function setMeanTimeToRecover($meanTimeToRecover)
    {
        $this->meanTimeToRecover = $meanTimeToRecover;
    }

    /**
     * @param int $meanTimeToRecover
     */
    public function addNewFailure($timeToRecover)
    {
        $failures = $this->getFailureCount();
        $fullTimeToRecover = $failures * $this->getMeanTimeToRecover() + $timeToRecover;
        $this->setMeanTimeToRecover(($fullTimeToRecover / ($failures + 1)) + 1);
        $this->incFailureCount();
    }
}
