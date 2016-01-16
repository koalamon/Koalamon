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
        $this->integrations[] = $integration;
    }

    /**
     * @return Integration[]
     */
    public function getIntegrations()
    {
        return $this->integrations;
    }
}