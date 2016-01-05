<?php

namespace Bauer\IncidentDashboard\CoreBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Doctrine\ORM\Mapping as ORM;

/**
 * Translation
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Translation
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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="translations")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     **/
    private $project;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=255)
     */
    private $identifier;

    /**
     * @var string
     *
     * @ORM\Column(name="system", type="string", length=255, nullable=true))
     */
    private $system;

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
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    public function __construct()
    {
        $this->created = new \DateTime("now");
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
     * @return mixed
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function translate(Event $event)
    {
        if (!is_null($this->getUrl())) {
            $event->setUrl($this->getUrl());
        }

        if (!is_null($this->getType())) {
            $event->setType($this->getType());
        }

        if (!is_null($this > $this->getSystem())) {
            $event->setSystem($this->getSystem());
        }

        if (!is_null($this->getMessage())) {
            $event->setMessage($this->getMessage());
        }

        return $event;
    }
}

