<?php

namespace Bauer\IncidentDashboard\CoreBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bauer\IncidentDashboard\CoreBundle\Entity\EventRepository")
 */
class Event implements \JsonSerializable
{
    const STATUS_SUCCESS = "success";
    const STATUS_FAILURE = "failure";

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
     * @ORM\Column(name="system", type="string", length=255)
     */
    private $system;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url = "";

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type = "";

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var integer
     *
     * @ORM\Column(name="value", type="float", nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastStatusChange;

    /**
     * @ORM\Column(name="isUnique", type="boolean")
     */
    private $unique = false;

    /**
     * @ORM\Column(name="isStatusChange", type="boolean")
     */
    private $isStatusChange = false;

    /**
     * @ORM\ManyToOne(targetEntity="EventIdentifier", inversedBy="events")
     * @ORM\JoinColumn(name="event_identifier_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     **/
    private $eventIdentifier;

    public function __construct()
    {
        $this->created = new \DateTime("now");
        $this->lastStatusChange = $this->created;
    }

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
     * Set system
     *
     * @param string $system
     *
     * @return Event
     */
    public function setSystem($system)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * Get system
     *
     * @return string
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Event
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Event
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get created date
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function hasUrl()
    {
        return $this->url != "";
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return boolean
     */
    public function isUnique()
    {
        return $this->unique;
    }

    /**
     * @param boolean $unique
     */
    public function setUnique($unique)
    {
        $this->unique = $unique;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return boolean
     */
    public function getIsStatusChange()
    {
        return $this->isStatusChange;
    }

    /**
     * @param mixed $isStatusChange
     */
    public function setIsStatusChange($isStatusChange)
    {
        $this->isStatusChange = $isStatusChange;
    }

    /**
     * @return \DateTime
     */
    public function getLastStatusChange()
    {
        return $this->lastStatusChange;
    }

    /**
     * @param \DateTime $lastStatusChange
     */
    public function setLastStatusChange($lastStatusChange)
    {
        $this->lastStatusChange = $lastStatusChange;
    }

    /**
     * @return EventIdentifier
     */
    public function getEventIdentifier()
    {
        return $this->eventIdentifier;
    }

    /**
     * @param EventIdentifier $eventIdentifier
     */
    public function setEventIdentifier(EventIdentifier $eventIdentifier = null)
    {
        $this->eventIdentifier = $eventIdentifier;
    }

    function jsonSerialize()
    {
        return array("id" => $this->getId(),
            "system" => $this->getSystem(),
            "status" => $this->getStatus(),
            "message" => $this->getMessage(),
            "tool" => $this->getType(),
            "url" => $this->getUrl(),
            "created" => $this->getCreated()->format("d.m.Y H:i:s"),
            "stated" => $this->getLastStatusChange()->format("d.m.Y H:i:s"),
            "project" => array("name" => $this->getEventIdentifier()->getProject()->getName(),
                "id" => $this->getEventIdentifier()->getProject()->getId()));
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}

