<?php

namespace Koalamon\IntegrationBundle\Integration;

class IntegrationContainer
{
    private $integrations = array();

    /**
     * @return mixed
     */
    public function addIntegration(Integration $integration)
    {
        if (array_key_exists($integration->getName(), $this->integrations)) {
            $this->addIntegration(new Integration($integration->getName() . ' (1)',
                $integration->getImage(),
                $integration->getDescription(),
                $integration->getUrl()));
        } else {
            $this->integrations[$integration->getName()] = $integration;
        }
    }

    /**
     * @return Integration[]
     */
    public function getIntegrations()
    {
        ksort($this->integrations);
        return $this->integrations;
    }
}