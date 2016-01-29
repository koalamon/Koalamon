<?php

namespace Bauer\IncidentDashboard\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bauer\IncidentDashboard\CoreBundle\Entity\EventRepository")
 */
class System implements \JsonSerializable

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
     * @ORM\Column(name="identifier", type="string", length=255, nullable=true)
     */
    private $identifier;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="systems")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     **/
    private $project;

    /**
     * @ORM\OneToMany(targetEntity="System", mappedBy="parent")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $children;

    /**
     * @var System
     *
     * @ORM\ManyToOne(targetEntity="System", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param string $url
     */
    public function setImage($image)
    {
        $this->image = $image;
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
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return System[]
     */
    public function getSubsystems()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function addSubsystem(System $child)
    {
        $this->children[] = $child;
    }

    /**
     * @return System
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param System $parent
     */
    public function setParent(System $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return array
     */
    function jsonSerialize()
    {
        $subSystems = array();

        foreach ($this->children as $subSystem) {
            $subSystems[$subSystem->getId()] = $subSystem->jsonSerialize();
        }

        if ($this->parent) {
            $identifer = $this->parent->getIdentifier();
            $parent = $this->parent->getId();
        } else {
            $identifer = $this->getIdentifier();
            $parent = false;
        }

        return [
            'id' => $this->id,
            'identifier' => $identifer,
            'name' => $this->getName(),
            'url' => $this->getUrl(),
            'parent' => $parent,
            'project' => $this->getProject()->getIdentifier(),
            'image' => $this->getImage(),
            'subSystems' => $subSystems,
            'project' => $this->project->jsonSerialize()
        ];
    }
}
