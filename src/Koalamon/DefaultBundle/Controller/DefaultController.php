<?php

namespace Koalamon\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('KoalamonDefaultBundle:Default:index.html.twig');
    }

    public function imprintAction()
    {
        return $this->render('KoalamonDefaultBundle:Default:imprint.html.twig');
    }
}
