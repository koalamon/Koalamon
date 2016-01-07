<?php

namespace Koalamon\NotificationEngineBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Bauer\IncidentDashboard\CoreBundle\Entity\Tool;
use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Koalamon\NotificationEngineBundle\Entity\NotificationConfigurationRepository")
 */
class NotificationConfiguration
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="options", type="text", nullable=true)
     */
    private $options = 'a:0:{}';

    /**
     * @var string
     *
     * @ORM\Column(name="senderType", type="string", length=255)
     */
    private $senderType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notifyAll", type="boolean")
     */
    private $notifyAll = false;

    /**
     * @ORM\ManyToOne(targetEntity="Bauer\IncidentDashboard\CoreBundle\Entity\Project", inversedBy="notificationConfigurations")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     **/
    private $project;

    /**
     * @var Tool[]
     *
     * @ORM\ManyToMany(targetEntity="Bauer\IncidentDashboard\CoreBundle\Entity\Tool", inversedBy="notificationChannels")
     * @ORM\JoinTable(name="notificationChannels_tools")
     */
    private $connectedTools;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return unserialize($this->options);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = serialize($options);
    }

    /**
     * @return string
     */
    public function getSenderType()
    {
        return $this->senderType;
    }

    /**
     * @param string $senderType
     */
    public function setSenderType($senderType)
    {
        $this->senderType = $senderType;
    }

    /**
     * @param mixed $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @param boolean $notifyAll
     */
    public function setNotifyAll($notifyAll)
    {
        $this->notifyAll = $notifyAll;
    }

    /**
     * @return boolean
     */
    public function isNotifyAll()
    {
        return $this->notifyAll;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Tool[]
     */
    public function getConnectedTools()
    {
        return $this->connectedTools;
    }

    public function clearConnectedTools()
    {
        $this->connectedTools->clear();
    }

    public function addConnectedTool(Tool $tool)
    {
        return $this->connectedTools[] = $tool;
    }

    public function isConnectedTool(Tool $tool)
    {
        return $this->connectedTools->contains($tool);
    }
}

