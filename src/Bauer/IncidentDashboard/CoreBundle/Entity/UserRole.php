<?php

namespace Bauer\IncidentDashboard\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class UserRole
{
    const ROLE_OWNER = 0;
    const ROLE_ADMIN = 100;
    const ROLE_COLLABORATOR = 200;
    const ROLE_WATCHER = 300;
    const ROLE_ANONYMOUS = 10000;

    private static $roleNames = array(0 => "Owner",
        100 => "Administrator",
        200 => "Collaborator",
        300 => "Watcher",
        10000 => "Anonymous");

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="UserRoles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="UserRoles")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     **/
    private $project;

    /**
     * @var string
     * @ORM\Column(name="role", type="integer", length=255)
     */
    private $role;

    /**
     * @var boolean
     * @ORM\Column(name="favorite", type="boolean")
     */
    private $favorite = false;

    public function __construct(User $user, Project $project, $role)
    {
        $this->user = $user;
        $this->role = $role;
        $this->project = $project;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    public function getRoleName()
    {
        return self::$roleNames[$this->role];
    }

    public static function getRoles()
    {
        return self::$roleNames;
    }

    public static function getPossibleRoles()
    {
        $roles = array();

        foreach (self::$roleNames as $key => $roleName) {
            if ($key >= 100 && $key < 10000) {
                $roles[$key] = $roleName;
            }
        }
        return $roles;
    }

    /**
     * @return boolean
     */
    public function isFavorite()
    {
        return $this->favorite;
    }

    /**
     * @param boolean $favorite
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;
    }
}
