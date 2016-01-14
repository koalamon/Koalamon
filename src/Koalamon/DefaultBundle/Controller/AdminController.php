<?php

namespace Koalamon\DefaultBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Koalamon\DefaultBundle\EventListener\AdminMenuEvent;
use Koalamon\DefaultBundle\Menu\Element;
use Koalamon\DefaultBundle\Menu\Menu;
use Symfony\Component\EventDispatcher\EventDispatcher;

class AdminController extends ProjectAwareController
{
    public function renderMenuAction(Project $project, $currentUri)
    {
        $menu = new Menu();

        $menu->addElement(new Element($this->generateUrl('koalamon_default_project_admin', ['project' => $project->getIdentifier()], true),
            'Project', 'menu_admin_project'));

        $menu->addElement(new Element($this->generateUrl('koalamon_default_user_admin', ['project' => $project->getIdentifier()], true),
            'Collaborators', 'menu_admin_user'));

        $menu->addElement(new Element($this->generateUrl('koalamon_default_system_admin', ['project' => $project->getIdentifier()], true),
            'Systems', 'menu_admin_systems'));

        $menu->addElement(new Element($this->generateUrl('koalamon_default_tool_admin', ['project' => $project->getIdentifier()], true),
            'Tools', 'menu_admin_tools'));

        $dispatcher = $this->get('event_dispatcher');
        /** @var EventDispatcher $dispatcher */

        $dispatcher->dispatch('koalamon.admin.menu', new AdminMenuEvent($menu, $project));

        return $this->render('KoalamonDefaultBundle:Admin:menu.html.twig', ['menu' => $menu, 'currentUri' => $currentUri]);
    }
}
