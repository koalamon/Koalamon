<?php

namespace Koalamon\Integration\MissingRequestBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Koalamon\Integration\MissingRequestBundle\Entity\Collection;
use Koalamon\Integration\MissingRequestBundle\Entity\Request;
use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CollectionController extends ProjectAwareController
{
    public function newAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $collection = new Collection();
        return $this->render('KoalamonIntegrationMissingRequestBundle:Collection:new.html.twig', ['collection' => $collection]);
    }

    public function editAction(Collection $collection)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);
        return $this->render('KoalamonIntegrationMissingRequestBundle:Collection:new.html.twig', ['collection' => $collection]);
    }

    public function deleteAction(Collection $collection)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        if ($this->getUser()->getUserRole($collection->getProject())->getRole() > UserRole::ROLE_ADMIN) {
            throw new AccessDeniedException('You are not allowed to delete this collection');
        }

        $em = $this->getDoctrine()->getManager();

        if (!is_null($collection->getRequests())) {
            foreach ($collection->getRequests() as $oldRequest) {
                $em->remove($oldRequest);
            }
        }
        $em->flush();

        $em->remove($collection);
        $em->flush();

        return $this->redirectToRoute('koalamon_integration_missing_request_homepage');
    }

    public function storeAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        if ($request->get('id')) {
            $collection = $this->getDoctrine()->getRepository('KoalamonIntegrationMissingRequestBundle:Collection')->find($request->get('id'));
            if ($this->getUser()->getUserRole($collection->getProject())->getRole() > UserRole::ROLE_ADMIN) {
                throw new AccessDeniedException('You are not allowed to access this page with the given parameters');
            }
        } else {
            $collection = new Collection();
            $collection->setProject($this->getProject());
        }

        $collection->setName($request->get('name'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($collection);

        if (!is_null($collection->getRequests())) {
            foreach ($collection->getRequests() as $oldRequest) {
                $em->remove($oldRequest);
            }
        }
        $em->flush();

        $requests = $request->get('request');
        foreach ($requests as $missingRequest) {
            if ($missingRequest['pattern'] != '') {
                $requestObj = new Request();
                $requestObj->setCollection($collection);
                $requestObj->setName($missingRequest['name']);
                $requestObj->setPattern($missingRequest['pattern']);
                $em->persist($requestObj);
            }
        }

        $em->flush();

        return $this->redirectToRoute('koalamon_integration_missing_request_homepage');
    }
}
