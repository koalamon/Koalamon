<?php

namespace Koalamon\IntegrationBundle\Integration;

class Integration
{
    private $name;
    private $image;
    private $description;
    private $url;

    /**
     * Integration constructor.
     *
     * @param $name
     * @param $image
     * @param $description
     * @param $url
     */
    public function __construct($name, $image, $description, $url)
    {
        $this->name = $name;
        $this->image = $image;
        $this->description = $description;
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
}