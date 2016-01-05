<?php

namespace Koalamon\DefaultBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InformationController extends Controller
{
    public function currentAction(Project $project)
    {
        $informations = $this->getDoctrine()->getRepository('KoalamonDefaultBundle:Information')->findCurrentInformation($project);

        return $this->render('KoalamonDefaultBundle:Information:list.html.twig', ['informations' => $informations]);
    }
}
