<?php

namespace Bauer\IncidentDashboard\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as FosUser;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bauer\IncidentDashboard\CoreBundle\Entity\UserRepository")
 */
class User extends FosUser implements \JsonSerializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    private $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="github_id", type="string", length=255, nullable=true)
     */
    private $githubId;

    /**
     * @var UserRole[]
     *
     * @ORM\OneToMany(targetEntity="UserRole", mappedBy="user")
     */
    private $userRoles;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=255, nullable=true)
     */
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = $this->generateApiKey();
        return parent::__construct();
    }

    private function generateApiKey()
    {
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

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
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
        return $this;
    }

    /**
     * Set githubId
     *
     * @param string $githubId
     *
     * @return User
     */
    public function setGithubId($githubId)
    {
        $this->githubId = $githubId;
        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Get githubId
     *
     * @return string
     */
    public function getGithubId()
    {
        return $this->githubId;
    }

    /**
     * @return mixed
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @param mixed $urls
     */
    public function addUrl($url)
    {
        $this->urls[] = $url;
    }



    /**
     * @return mixed
     */
    public function getProjects()
    {
        $projects = array();

        foreach ($this->userRoles as $userRole) {
            $projects[$userRole->getProject()->getName()] = $userRole->getProject();
        }

        ksort($projects);

        return $projects;
    }

    public function hasFavoriteProjects()
    {
        return count($this->getFavoriteProjects()) > 0;
    }

    public function getFavoriteProjects()
    {
        $projects = array();

        foreach ($this->userRoles as $userRole) {
            if ($userRole->isFavorite()) {
                $projects[$userRole->getProject()->getName()] = $userRole->getProject();
            }
        }
        ksort($projects);
        return $projects;
    }

    /**
     * @return UserRole[]
     */
    public function getUserRole(Project $project)
    {
        foreach ($this->userRoles as $userRole) {
            if ($userRole->getProject() == $project) {
                return $userRole;
            }
        }
        return new UserRole($this, $project, UserRole::ROLE_ANONYMOUS);
    }

    /**
     * @return UserRole[]
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    public function clearRoles(Project $project)
    {
        foreach ($this->userRoles as $userRole) {
            if ($userRole->getProject() == $project) {
                $this->userRoles->removeElement($userRole);
            }
        }
    }

    /**
     * @return bool
     */
    public function hasUserRole(Project $project, $role)
    {
        foreach ($this->userRoles as $userRole) {
            if ($userRole->getProject() == $project) {
                return $role >= $userRole->getRole();
            }
        }
        return false;
    }

    function jsonSerialize()
    {
        $user = new \stdClass();
        $user->username = $this->getUsername();
        $user->email = $this->getEmail();
        if (null !== $this->getFacebookId()) {
            $user->facebookId = $this->getFacebookId();
        }
        if (null !== $this->getGithubId()) {
            $user->githubId = $this->getGithubId();
        }

        return $user;
    }
}
