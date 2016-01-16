<?php

namespace Koalamon\Integration\KoalaPingBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Bauer\IncidentDashboard\CoreBundle\Entity\System;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class KoalaPingConfig
{
    const STATUS_SELECTED = 'selected';
    const STATUS_ALL = 'all';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bauer\IncidentDashboard\CoreBundle\Entity\Project", inversedBy="koalaPingSystems")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     **/
    private $project;

    /**
     * @ORM\Column(name="status", type="string")
     */
    private $status = self::STATUS_SELECTED;

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
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
