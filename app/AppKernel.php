<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),

            // Custom Bundles
            new Koalamon\Bundle\IncidentDashboardBundle\KoalamonIncidentDashboardBundle(),

            new Koalamon\Bundle\StatBundle\KoalamonStatBundle(),
            new Koalamon\Bundle\DefaultBundle\KoalamonDefaultBundle(),
            new Koalamon\Bundle\WebhookBundle\KoalamonWebhookBundle(),
            new Koalamon\Bundle\ConsoleBundle\KoalamonConsoleBundle(),
            new Koalamon\Bundle\RestBundle\KoalamonRestBundle(),
            new Koalamon\NotificationBundle\KoalamonNotificationBundle(),

            new Koalamon\Bundle\InformationBundle\KoalamonInformationBundle(),
            new Koalamon\Bundle\IntegrationBundle\KoalamonIntegrationBundle(),
            new Koalamon\Bundle\PluginBundle\KoalamonPluginBundle(),
            new Koalamon\Bundle\Integration\KoalaPingBundle\KoalamonIntegrationKoalaPingBundle(),
            new Koalamon\Bundle\Integration\WebhookBundle\KoalamonIntegrationWebhookBundle(),
            new Koalamon\Bundle\Integration\MissingRequestBundle\KoalamonIntegrationMissingRequestBundle(),
            new Koalamon\Bundle\Integration\GooglePageSpeedBundle\KoalamonIntegrationGooglePageSpeedBundle(),
            new Koalamon\Bundle\Integration\JsErrorScannerBundle\KoalamonIntegrationJsErrorScannerBundle(),
            new Koalamon\Bundle\GeckoBoardBundle\KoalamonGeckoBoardBundle(),
            new Koalamon\Integration\SiteInfoBundle\KoalamonIntegrationSiteInfoBundle(),

        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }
        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
