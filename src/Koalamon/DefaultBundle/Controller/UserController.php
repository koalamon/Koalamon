<?php

namespace Koalamon\DefaultBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserController extends Controller
{
    public function indexAction()
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        return $this->render('KoalamonDefaultBundle:User:index.html.twig');
    }
}
