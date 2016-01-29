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
            new Bauer\IncidentDashboard\CoreBundle\BauerIncidentDashboardCoreBundle(),

            new Koalamon\StatBundle\KoalamonStatBundle(),
            new Koalamon\DefaultBundle\KoalamonDefaultBundle(),
            new Koalamon\WebhookBundle\KoalamonWebhookBundle(),
            new Koalamon\ConsoleBundle\KoalamonConsoleBundle(),
            new Koalamon\RestBundle\KoalamonRestBundle(),
            new Koalamon\NotificationBundle\KoalamonNotificationBundle(),
            new Koalamon\ArchiveBundle\KoalamonArchiveBundle(),
            new Koalamon\InformationBundle\KoalamonInformationBundle(),
            new Koalamon\IntegrationBundle\KoalamonIntegrationBundle(),
            new Koalamon\PluginBundle\KoalamonPluginBundle(),
            new Koalamon\Integration\KoalaPingBundle\KoalamonIntegrationKoalaPingBundle(),
            new Koalamon\Integration\WebhookBundle\KoalamonIntegrationWebhookBundle(),
            new Koalamon\Integration\MissingRequestBundle\KoalamonIntegrationMissingRequestBundle(),
            new Koalamon\Integration\JsErrorScannerBundle\KoalamonIntegrationJsErrorScannerBundle(),
            new Koalamon\GeckoBoardBundle\KoalamonGeckoBoardBundle(),
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
