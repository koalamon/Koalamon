<?php

namespace Bauer\IncidentDashboard\CoreBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 */
class RawEvent
{
    const STATUS_SUCCESS = "success";
    const STATUS_FAILURE = "failure";

    /**
     * @var string
     */
    private $system;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $url = "";

    /**
     * @var string
     */
    private $type = "";

    private $value;

    /**
     * @var string
     */
    private $message;

    private $unique = false;

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
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function isUnique()
    {
        return $this->unique;
    }

    /**
     * @param mixed $unique
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
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
