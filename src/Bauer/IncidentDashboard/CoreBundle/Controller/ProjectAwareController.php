<?php

namespace Bauer\IncidentDashboard\CoreBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProjectAwareController extends Controller
{
    private $project;

    const ACCESS_OWNER = "owner";
    const ACCESS_COLLABORATOR = "collaborator";

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->initializeProject();
    }

    /**
     * Perform some operations after controller initialized and container set.
     */
    private function initializeProject()
    {
        $project = $this->getRequest()->get("project");

        $this->project = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:Project')
            ->findOneBy(array("identifier" => $project));
    }

    /**
     * Returns the current project, that is retrieved from the url
     *
     * @return Project
     */
    protected function getProject()
    {
        return $this->project;
    }

    protected function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Throws an exception if the current user hasn't the needed rights.
     *
     * @param $type
     * @throws AccessDeniedException
     */
    protected function assertUserRights($role)
    {
        if ($this->getProject() == "") {
            throw new AccessDeniedException('You are not allowed to call this action');
        }

        $userRole = $this->getUser()->getUserRole($this->getProject());

        if ($role < $userRole->getRole()) {
            throw new AccessDeniedException('You are not allowed to call this action');
        }
    }

    public function render($view, array $parameters = array(), Response $response = null)
    {
        if (!array_key_exists('project', $parameters)) {
            $parameters['project'] = $this->project;
        }

        return parent::render($view, $parameters, $response);
    }
}
