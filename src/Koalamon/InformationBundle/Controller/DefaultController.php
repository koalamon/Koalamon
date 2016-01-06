<?php

namespace Koalamon\InformationBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function currentAction(Project $project)
    {
        $informations = $this->getDoctrine()->getRepository('KoalamonInformationBundle:Information')->findCurrentInformation($project);

        return $this->render('KoalamonInformationBundle:Default:list.html.twig', ['informations' => $informations]);
    }
}
