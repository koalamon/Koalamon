<?php

namespace Koalamon\Integration\WebhookBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;

class DefaultController extends ProjectAwareController
{
    public function newRelicAction($hookName)
    {
        return $this->render('KoalamonIntegrationWebhookBundle:Default:' . $hookName . '.html.twig');
    }
}
