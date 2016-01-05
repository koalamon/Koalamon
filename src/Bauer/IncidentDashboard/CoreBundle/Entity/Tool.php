<?php

namespace Bauer\IncidentDashboard\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bauer\IncidentDashboard\CoreBundle\Entity\EventRepository")
 */
class Tool implements \JsonSerializable
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;


    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;


    /**
     * @var string
     *
     * @ORM\Column(name="notify", type="boolean")
     */
    private $notify = false;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="elements")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     **/
    private $project;

    /**
     * @var integer
     *
     * @ORM\Column(name="`interval`", type="integer", nullable=true)
     */
    private $interval = null;

    /**
     * @var boolean
     *
     * @ORM\Column(name="alwaysActive", type="boolean", nullable=true)
     */
    private $alwaysActive = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="systemSpecific", type="boolean", nullable=true)
     */
    private $systemSpecific = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active = false;

    /**
     * @ORM\OneToMany(targetEntity="EventIdentifier", mappedBy="tool")
     */
    private $eventIdentifiers;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getNotify()
    {
        return $this->notify;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @param string $notify
     */
    public function setNotify($notify)
    {
        $this->notify = $notify;
    }

    /**
     * @param Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return int
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param int $interval
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
    }

    /**
     * @return boolean
     */
    public function isAlwaysActive()
    {
        return $this->alwaysActive;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return EventIdentifier[]
     */
    public function getEventIdentifiers()
    {
        return $this->eventIdentifiers;
    }

    public function hasEventIdentifiers()
    {
        return count($this->eventIdentifiers) > 0;
    }

    /**
     * @return boolean
     */
    public function isSystemSpecific()
    {
        return $this->systemSpecific;
    }

    /**
     * @param boolean $systemSpecific
     */
    public function setSystemSpecific($systemSpecific)
    {
        $this->systemSpecific = $systemSpecific;
    }
    
    public function jsonSerialize()
    {
        return [
            "identifier" => $this->getIdentifier(),
            "name" => $this->getName(),
            "image" => $this->getImage(),
            "notify" => $this->getNotify(),
            "description" => $this->getDescription(),
            "project" => $this->getProject()->getIdentifier()
        ];
    }
}
