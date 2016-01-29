<?php

namespace Koalamon\DefaultBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\System;
use Bauer\IncidentDashboard\CoreBundle\Entity\Translation;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TranslationController extends ProjectAwareController
{
    public function adminAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);
        return $this->render('KoalamonDefaultBundle:Translation:admin.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $translationObject = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:Translation')
            ->find($request->get("translation_id"));

        $em = $this->getDoctrine()->getManager();
        $em->remove($translationObject);
        $em->flush();

        $this->addFlash('success', 'Translation successfully deleted.');
        return $this->redirectToRoute('koalamon_default_admin_translation_home');
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function storeAction(Request $request)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $translation = $request->get("translation");

        if (array_key_exists("id", $translation)) {
            $translationObject = $this->getDoctrine()
                ->getRepository('BauerIncidentDashboardCoreBundle:Translation')
                ->find($translation["id"]);
        } else {
            $translationObject = new Translation();
            $translationObject->setProject($this->getProject());
        }

        if ($translation["identifier"] != "") {
            $translationObject->setIdentifier($translation["identifier"]);
        } else {
            $this->addFlash('notice', 'The parameter "identifier" is required');
            return $this->redirect($this->generateUrl('koalamon_default_admin_translation_home', array("project" => $this->getProject()->getIdentifier())));
        }

        if ($translation["message"] != "") {
            $translationObject->setMessage($translation["message"]);
        } else {
            $translationObject->setMessage(null);
        }

        if ($translation["system"] != "") {
            $translationObject->setSystem($translation["system"]);
        } else {
            $translationObject->setSystem(null);
        }

        if ($translation["type"] != "") {
            $translationObject->setType($translation["type"]);
        } else {
            $translationObject->setType(null);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($translationObject);
        $em->flush();

        $this->addFlash('success', 'Translation "' . $translationObject->getIdentifier() . '" successfully saved.');
        return $this->redirectToRoute('koalamon_default_admin_translation_home');
    }
}
