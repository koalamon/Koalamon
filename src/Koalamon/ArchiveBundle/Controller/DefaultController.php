<?php

namespace Koalamon\ArchiveBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('KoalamonArchiveBundle:Default:index.html.twig', array('name' => $name));
    }
}
