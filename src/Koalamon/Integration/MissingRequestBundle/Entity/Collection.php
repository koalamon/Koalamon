<?php

namespace Koalamon\Integration\MissingRequestBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Bauer\IncidentDashboard\CoreBundle\Entity\System;
use Doctrine\ORM\Mapping as ORM;

/**
 * Collection
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Koalamon\Integration\MissingRequestBundle\Entity\CollectionRepository")
 */
class Collection implements \JsonSerializable
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
     * @ORM\ManyToOne(targetEntity="Bauer\IncidentDashboard\CoreBundle\Entity\Project", inversedBy="missingRequestGroups")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     **/
    private $project;

    /**
     * @var Request[]
     *
     * @ORM\OneToMany(targetEntity="Request", mappedBy="collection")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $requests;


    /**
     * @ORM\ManyToMany(targetEntity="Bauer\IncidentDashboard\CoreBundle\Entity\System")
     * @ORM\JoinTable(name="collections_systems",
     *      joinColumns={@ORM\JoinColumn(name="collection_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="system_id", referencedColumnName="id")}
     *      )
     */
    private $systems;

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
     * Set name
     *
     * @param string $name
     *
     * @return Collection
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return Request
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * @param Request $requests
     */
    public function setRequests(Request $requests)
    {
        $this->requests = $requests;
    }

    /**
     * @return System[]
     */
    public function getSystems()
    {
        return $this->systems;
    }

    public function removeSystem(System $system)
    {
        if ($this->systems->contains($system)) {
            $this->systems->removeElement($system);
        }
    }

    public function clearSystems()
    {
        $this->systems->clear();
    }

    /**
     * @param System $systems
     */
    public function addSystem(System $system)
    {
        $this->systems[] = $system;
    }

    function jsonSerialize()
    {
        $collectionRequests = array();

        foreach($this->requests as $request) {
            $collectionRequests[] = $request->jsonSerialize();
        }

        return [
            'name' => $this->name,
            'requests' => $collectionRequests
        ];
    }
}
