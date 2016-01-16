<?php

namespace Koalamon\Integration\KoalaPingBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Entity\System;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class KoalaPingSystem
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
}
