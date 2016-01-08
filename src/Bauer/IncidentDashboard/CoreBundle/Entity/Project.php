<?php

namespace Bauer\IncidentDashboard\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Event
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="identifier_idx", columns={"identifier"})})
 * @ORM\Entity(repositoryClass="Bauer\IncidentDashboard\CoreBundle\Entity\ProjectRepository")
 */
class Project implements \JsonSerializable
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
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=255)
     */
    private $apiKey;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="openIncidentCount", type="integer", nullable=true)
     */
    private $openIncidentCount = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="public", type="boolean", nullable=true)
     */
    private $public = false;

    /**
     * @var string
     *
     * @ORM\Column(name="eventCount", type="integer", nullable=true)
     */
    private $eventCount = 0;

    /**
     * @var UserRole[]
     *
     * @ORM\OneToMany(targetEntity="UserRole", mappedBy="project")
     */
    private $userRoles;

    /**
     * @var Tool[]
     *
     * @ORM\OneToMany(targetEntity="Tool", mappedBy="project")
     * @ORM\OrderBy({"identifier" = "ASC"})
     */
    private $tools;

    /**
     * @ORM\OneToMany(targetEntity="EventIdentifier", mappedBy="project")
     */
    private $eventIdentifiers;

    /**
     * @ORM\OneToMany(targetEntity="System", mappedBy="project")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $systems;

    /**
     * @ORM\OneToMany(targetEntity="Koalamon\InformationBundle\Entity\Information", mappedBy="project")
     */
    private $informations;

    /**
     * @ORM\OneToMany(targetEntity="Translation", mappedBy="project")
     */
    private $translations;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastStatusChange;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function __construct()
    {
        $this->apiKey = $this->generateApiKey();
    }

    private function generateApiKey()
    {
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * @return User[]
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    public function getUserRole(User $user)
    {
        foreach ($this->userRoles as $userRole) {
            if ($userRole->getUser() == $user) {
                return $this->userRole;
            }
        }
    }

    public function addUserRole(UserRole $userRole)
    {
        $this->userRoles[] = $userRole;
    }

    /**
     * @return string
     */
    public function getSlackWebhook()
    {
        return $this->slackWebhook;
    }

    /**
     * @return Tool[]
     */
    public function getTools($onlyActive = true)
    {
        if ($onlyActive) {
            $tools = array();
            foreach ($this->tools as $tool) {
                if ($tool->isActive()) {
                    $tools[] = $tool;
                }
            }
            return $tools;
        }
        return $this->tools;
    }

    /**
     * @return System[]
     */
    public function getSystems()
    {
        return $this->systems;
    }

    /**
     * @return mixed
     */
    public function getTranslations()
    {
        return $this->translations;
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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param string $slackWebhook
     */
    public function setSlackWebhook($slackWebhook)
    {
        $this->slackWebhook = $slackWebhook;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;
    }

    public function addSystem(System $system)
    {
        $this->systems[] = $system;
    }

    /**
     * @return string
     */
    public function incOpenIncidentCount()
    {
        $this->openIncidentCount++;
    }

    /**
     * @param string $openIncidentCount
     */
    public function decOpenIncidentCount()
    {
        $this->openIncidentCount = max(0, $this->openIncidentCount - 1);
    }

    /**
     * @return string
     */
    public function getOpenIncidentCount()
    {
        return $this->openIncidentCount;
    }

    /**
     * @return \DateTime
     */
    public function getLastStatusChange()
    {
        return $this->lastStatusChange;
    }

    /**
     * @param mixed $lastStatusChange
     */
    public function setLastStatusChange(\DateTime $lastStatusChange)
    {
        $this->lastStatusChange = $lastStatusChange;
    }

    /**
     * @return EventIdentifier[]
     */
    public function getEventIdentifiers()
    {
        return $this->eventIdentifiers;
    }

    /**
     * @return \stdClass
     */
    function jsonSerialize()
    {
        $project = new \stdClass();
        $project->name = $this->getName();
        $project->description = $this->getDescription();
        $project->identifier = $this->getIdentifier();
        $project->owner = $this->getOwner()->getUsernameCanonical();
        $project->slackWebhook = $this->getSlackWebhook();

        $systems = [];
        foreach ($this->getSystems() as $system) {
            $systems[] = $system->getIdentifier();
        }
        if (0 < count($systems)) {
            $project->systems = $systems;
        }
        $users = [];
        foreach ($this->getUsers() as $user) {
            $users[] = $user->getUsernameCanonical();
        }
        if (0 < count($users)) {
            $project->users = $users;
        }

        return $project;
    }

    /**
     * @return integer
     */
    public function getEventCount()
    {
        $count = 0;
        foreach ($this->getEventIdentifiers() as $identifer) {
            $count += $identifer->getEventCount();
        }

        return $count;
    }

    /**
     * @return integer
     */
    public function getFailedEventCount()
    {
        $count = 0;
        foreach ($this->getEventIdentifiers() as $identifer) {
            $count += $identifer->getFailedEventCount();
        }

        return $count;
    }

    public function getFailureCount()
    {
        $count = 0;
        foreach ($this->getEventIdentifiers() as $identifier) {
            $count += $identifier->getFailureCount();
        }
        return $count;
    }

    /**
     * @return integer
     */
    public function getFailureRate()
    {
        $eventCount = $this->getEventCount();

        if ($eventCount == 0) {
            return 0;
        }
        return $this->getFailedEventCount() / $eventCount * 100;
    }

    /**
     * @return string
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param string $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }
}

