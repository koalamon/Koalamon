<?php

namespace Koalamon\DefaultBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CollaboratorController extends ProjectAwareController
{
    public function adminAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        return $this->render('KoalamonDefaultBundle:User:admin.html.twig', array('roles' => UserRole::getRoles()));
    }

    public function removeCollaboratorAction(Request $request)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $userId = $request->get("userId");

        $user = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:User')
            ->find($userId);

        $currentUserRole = $this->getUser()->getUserRole($this->getProject());

        if (!is_null($user) && $currentUserRole->getRole() < $user->getUserRole($this->getProject())->getRole()) {
            $em = $this->getDoctrine()->getManager();

            $userRoles = $this->getDoctrine()
                ->getRepository('BauerIncidentDashboardCoreBundle:UserRole')
                ->findBy(array("project" => $this->getProject(), 'user' => $user));

            foreach ($userRoles as $userRole) {
                $em->remove($userRole);
            }

            $em->flush();
        }

        return $this->redirectToRoute('koalamon_default_user_admin');
    }


    public function addCollaboratorAction(Request $request)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $userId = $request->get("userId");
        $role = $request->get("role");

        $user = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:User')
            ->find($userId);

        $currentUserRole = $this->getUser()->getUserRole($this->getProject());

        if ($currentUserRole->getRole() >= $role) {
            throw new AccessDeniedException('You are not allowed to call this action');
        }

        if ($currentUserRole->getRole() >= $user->getUserRole($this->getProject())->getRole()) {
            throw new AccessDeniedException('You are not allowed to call this action');
        }

        if (!is_null($user)) {
            $em = $this->getDoctrine()->getManager();

            // only one role allowed
            $userRoles = $this->getDoctrine()
                ->getRepository('BauerIncidentDashboardCoreBundle:UserRole')
                ->findBy(array("project" => $this->getProject(), 'user' => $user));

            foreach ($userRoles as $userRole) {
                $em->remove($userRole);
            }

            $em->flush();

            $userRole = new UserRole($user, $this->getProject(), $role);
            $em->persist($userRole);
            $em->flush();
        }

        return $this->redirectToRoute('koalamon_default_user_admin');
    }
}
