<?php

namespace Koalamon\DefaultBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\System;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SystemController extends ProjectAwareController
{
    public function adminAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $systems = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:System')
            ->findBy(['project' => $this->getProject(), 'parent' => null]);

        return $this->render('KoalamonDefaultBundle:System:admin.html.twig', ['systems' => $systems]);
    }

    private function getJsonResponse($status, $message, $elementId, $id = 0)
    {
        return new JsonResponse(['status' => $status, 'message' => $message, 'id' => $id, 'elementId' => $elementId]);
    }

    public function storeSystemAction(Request $request)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $system = $request->get('system');

        if (array_key_exists("id", $system) && $system['id'] != '') {
            $systemObject = $this->getDoctrine()
                ->getRepository('BauerIncidentDashboardCoreBundle:System')
                ->find($system["id"]);
        } else {
            $systemObject = new System();
            $systemObject->setProject($this->getProject());
        }

        if (array_key_exists("parent", $system)) {
            $parent = $this->getDoctrine()
                ->getRepository('BauerIncidentDashboardCoreBundle:System')
                ->find($system["parent"]);

            $systemObject->setParent($parent);
        }

        if ($system["identifier"] != "") {
            $systemObject->setIdentifier($system["identifier"]);
        } else if (!array_key_exists("parent", $system)) {
            return $this->getJsonResponse('failure', 'The parameter "identifier" is required', (int)$system['elementId']);
        }

        if ($system["url"] != "" && !filter_var($system['url'], FILTER_VALIDATE_URL) === false) {
            $systemObject->setUrl($system["url"]);
        } else {
            return $this->getJsonResponse('failure', 'The parameter "URL" requires a valid URL', (int)$system['elementId']);
        }

        if ($system["name"] != "") {
            $systemObject->setName($system["name"]);
        } else {
            $systemObject->setName($system['url']);
        }

        if ($system["description"] != "") {
            $systemObject->setDescription($system["description"]);
        } else {
            $systemObject->setDescription(null);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($systemObject);
        $em->flush();

        return $this->getJsonResponse('success', 'System "' . $systemObject->getName() . '" successfully saved.', (int)$system['elementId'], $systemObject->getId());
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteSystemAction(Request $request)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $systemObject = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:System')
            ->find($request->get("system_id"));

        $em = $this->getDoctrine()->getManager();

        foreach ($systemObject->getSubsystems() as $child) {
            $em->remove($child);
        }

        $em->remove($systemObject);
        $em->flush();

        return $this->getJsonResponse('success', 'System "' . $systemObject->getName() . '" deleted . ', '', $request->get("system_id"));
    }
}
