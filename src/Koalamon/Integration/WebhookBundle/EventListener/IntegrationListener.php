<?php

namespace Koalamon\Integration\WebhookBundle\EventListener;

use Koalamon\IntegrationBundle\EventListener\IntegrationInitEvent;
use Koalamon\IntegrationBundle\Integration\Integration;
use Koalamon\IntegrationBundle\Integration\IntegrationContainer;
use Symfony\Component\DependencyInjection\Container;

class IntegrationListener
{
    private $router;

    public function __construct(Container $container)
    {
        $this->router = $container->get('router');
    }

    private function initIntegrations(IntegrationContainer $container)
    {

    }

    public function onInit(IntegrationInitEvent $event)
    {
        $integrationContainer = $event->getIntegrationContainer();

        $url = $this->router->generate('koalamon_integration_webhook', ['project' => $event->getProject()->getIdentifier(), 'hookName' => 'newRelic']);
        $integrationContainer->addIntegration(new Integration('NewRelic', '/images/integrations/newrelic.png', 'Software Analytics with real-time data to bring it all together.', $url));

        $url = $this->router->generate('koalamon_integration_webhook', ['project' => $event->getProject()->getIdentifier(), 'hookName' => 'monitis']);
        $integrationContainer->addIntegration(new Integration('Monitis', '/images/integrations/monitis.png', 'All-in-one application monitoring platform.', $url));

        $url = $this->router->generate('koalamon_integration_webhook', ['project' => $event->getProject()->getIdentifier(), 'hookName' => 'webhook']);
        $integrationContainer->addIntegration(new Integration('Webhook', '/images/integrations/webhook.png', 'Simple webhook for default integrations.', $url));

        $url = $this->router->generate('koalamon_integration_webhook', ['project' => $event->getProject()->getIdentifier(), 'hookName' => 'appDynamics']);
        $integrationContainer->addIntegration(new Integration('AppDynamics', '/images/integrations/appdynamics.png', 'The next generation of Application Intelligence has arrived', $url));

        $url = $this->router->generate('koalamon_integration_webhook', ['project' => $event->getProject()->getIdentifier(), 'hookName' => 'jira']);
        $integrationContainer->addIntegration(new Integration('Jira', '/images/integrations/jira-logo-01.png', 'Your favourite issue tracker.', $url));
        /*
        $url = $this->router->generate('koalamon_integration_webhook', ['project' => $event->getProject()->getIdentifier(), 'hookName' => 'jenkins']);
        $integrationContainer->addIntegration(new Integration('Jenkins', '', 'Tool for Pinging your systems', $url));


*/
    }
}