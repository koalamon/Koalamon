<?php

namespace Koalamon\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function indexAction()
    {
        return $this->render('KoalamonDefaultBundle:User:index.html.twig');
    }
}
