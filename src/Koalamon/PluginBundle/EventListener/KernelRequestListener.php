<?php

namespace Koalamon\PluginBundle\EventListener;

use Koalamon\IntegrationBundle\Plugin\PluginHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;

class KernelRequestListener
{
    private $dispatcher;

    public function __construct(ContainerInterface $container)
    {
        $this->dispatcher = $container->get('event_dispatcher');
    }

    public function onKernelRequest()
    {
        $this->dispatcher->dispatch('koalamon.plugin.init');
    }
}