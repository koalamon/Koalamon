<?php

namespace Koalamon\GeckoBoardBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;

class DefaultController extends ProjectAwareController
{
    public function indexAction()
    {
        return $this->render('KoalamonGeckoBoardBundle:Default:index.html.twig');
    }
}
