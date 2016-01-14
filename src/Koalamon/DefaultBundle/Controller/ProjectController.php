<?php

namespace Koalamon\DefaultBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Bauer\IncidentDashboard\CoreBundle\Entity\System;
use Bauer\IncidentDashboard\CoreBundle\Entity\Translation;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProjectController extends ProjectAwareController
{
    public function adminAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);
        return $this->render('KoalamonDefaultBundle:Project:admin.html.twig', array('roles' => UserRole::getRoles()));
    }

    /**
     * @param Request $request
     */
    public function storeOptionsAction(Request $request)
    {
        $this->assertUserRights(UserRole::ROLE_OWNER);

        $project = $this->getProject();

        $options = $request->get('options');
        if ($options['isPublic'] == 'on') {
            $project->setPublic(true);
        } else {
            $project->setPublic(false);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();

        return $this->redirectToRoute('koalamon_default_project_admin');
    }

    public function createAction(Request $request)
    {
        $project = new Project();

        $form = $this->createFormBuilder($project)
            ->add('identifier', 'text', array('attr' => array('tooltip' => 'gift.tooltip.amount')))
            ->add('name', 'text')
            ->add('save', 'submit', array('label' => 'Create Project'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {

            $existingProject = $this->getDoctrine()
                ->getRepository('BauerIncidentDashboardCoreBundle:Project')
                ->findOneBy(['identifier' => $project->getIdentifier()]);

            if ($existingProject !== NULL) {
                $this->addFlash('notice', 'Project identifier already exists');
                return $this->render('KoalamonDefaultBundle:Project:create.html.twig', array('form' => $form->createView()));
            }

            $em = $this->getDoctrine()->getManager();

            $project->setOwner($this->getUser());

            $project->setLastStatusChange(new \DateTime());


            $em->persist($project);
            $em->flush();

            $userRole = new UserRole($this->getUser(), $project, UserRole::ROLE_OWNER);

            $em->persist($userRole);
            $em->flush();

            return $this->redirectToRoute('bauer_incident_dashboard_core_homepage', ['project' => $project->getIdentifier()]);
        }

        return $this->render('KoalamonDefaultBundle:Project:create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTranslationAction(Request $request)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $translationObject = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:Translation')
            ->find($request->get("translation_id"));

        $em = $this->getDoctrine()->getManager();
        $em->remove($translationObject);
        $em->flush();

        $this->addFlash('success', 'Translation successfully deleted.');
        return $this->redirectToRoute('koalamon_default_project_admin');
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function storeTranslationAction(Request $request)
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
            return $this->redirect($this->generateUrl('koalamon_default_project_admin', array("project" => $this->getProject()->getIdentifier())));
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
        return $this->redirectToRoute('koalamon_default_project_admin');
    }


}
