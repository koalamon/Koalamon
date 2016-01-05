<?php

namespace Koalamon\RestBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Entity\User;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends Controller
{
    public function startsWithAction(Request $request)
    {
        $nameStartsWith = $request->get("query");

        $users = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:User')
            ->findByUsername($nameStartsWith);

        $suggestions = array();
        foreach ($users as $user) {
            $suggestions[] = array("value" => $user->getUsername(), "data" => $user->getId());
        }

        return new JsonResponse(array("query" => $nameStartsWith, "suggestions" => $suggestions));
    }

    public function projectsAction(Request $request)
    {
        $username = $request->get('username');
        $apiKey = $request->get('api_key');

        $user = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:User')
            ->findOneBy(['usernameCanonical' => $username, 'apiKey' => $apiKey]);

        /** @var User $user */

        if (is_null($user)) {
            throw new AccessDeniedException('You are not allowed to call this action');
        }

        $userRoles = $user->getUserRoles();

        $projectsInfo = array();

        foreach ($userRoles as $userRole) {
            if ($userRole->getRole() <= UserRole::ROLE_COLLABORATOR) {
                $projectsInfo[] = ["name" => $userRole->getProject()->getName(),
                    "identifier" => $userRole->getProject()->getIdentifier(),
                    "apiKey" => $userRole->getProject()->getApiKey()];
            }
        }

        return new JsonResponse($projectsInfo);
    }
}
