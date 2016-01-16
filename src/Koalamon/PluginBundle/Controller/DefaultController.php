<?php

namespace Koalamon\PluginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('KoalamonPluginBundle:Default:index.html.twig', array('name' => $name));
    }
}
