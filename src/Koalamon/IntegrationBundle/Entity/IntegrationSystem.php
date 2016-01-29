<?php

namespace Koalamon\IntegrationBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Entity\System;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class IntegrationSystem
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
     * @ORM\ManyToOne(targetEntity="Bauer\IncidentDashboard\CoreBundle\Entity\System", inversedBy="koalaPingSystems")
     * @ORM\JoinColumn(name="system_id", referencedColumnName="id")
     **/
    private $system;

    /**
     * @ORM\ManyToOne(targetEntity="Bauer\IncidentDashboard\CoreBundle\Entity\Project", inversedBy="koalaPingSystems")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     **/
    private $project;

    /**
     * @var string
     * @ORM\Column(name="integration", type="string", length=255)
     */
    private $integration;

    /**
     * @var string
     * @ORM\Column(name="options", type="text", nullable=true)
     */
    private $options;

    /**
     * @return System
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * @param mixed $system
     */
    public function setSystem(System $system)
    {
        $this->system = $system;
        $this->project = $system->getProject();
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
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return string
     */
    public function getIntegration()
    {
        return $this->integration;
    }

    /**
     * @param string $integration
     */
    public function setIntegration($integration)
    {
        $this->integration = $integration;
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
