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
            new SymfonyCommunityDistribution\UserBundle\SymfonyCommunityDistributionUserBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),

            // Custom Bundles
            new Bauer\IncidentDashboard\CoreBundle\BauerIncidentDashboardCoreBundle(),
            new Bauer\IncidentDashboard\WebhookBundle\BauerIncidentDashboardWebhookBundle(),
            new Bauer\IncidentDashboard\StatBundle\BauerIncidentDashboardStatBundle(),

            new Koalamon\DefaultBundle\KoalamonDefaultBundle(),
            new \Koalamon\ConsoleBundle\KoalamonConsoleBundle(),
            new Koalamon\RestBundle\KoalamonRestBundle(),
            new Koalamon\NotificationEngineBundle\KoalamonNotificationEngineBundle(),
            new Koalamon\ArchiveBundle\KoalamonArchiveBundle(),
            new Koalamon\InformationBundle\KoalamonInformationBundle(),
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
