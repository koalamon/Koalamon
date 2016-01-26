<?php

namespace Koalamon\Integration\JsErrorScannerBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Koalamon\IntegrationBundle\Controller\SystemAwareIntegrationController;

class DefaultController extends SystemAwareIntegrationController
{
    const API_KEY = '27010d2a-5617-4da2-9f0d-993edf547abc';
    const INTEGRATION_ID = 'JsErrorScanner';

    protected function getIntegrationIdentifier()
    {
        return self::INTEGRATION_ID;
    }

    protected function getApiKey()
    {
        return self::API_KEY;
    }

    public function indexAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        return $this->render('KoalamonIntegrationJsErrorScannerBundle:Default:index.html.twig',
            [
                'config' => $this->getConfig(),
                'systems' => $this->getSystems(),
                'integratedSystems' => $this->getIntegratedSystems()
            ]);
    }
}
