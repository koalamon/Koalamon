<?php

namespace Koalamon\DefaultBundle\Menu;

class Element
{
    private $url;
    private $name;
    private $identifier;

    /**
     * Element constructor.
     * @param $url
     * @param $name
     * @param $identifier
     */
    public function __construct($url, $name, $identifier)
    {
        $this->url = $url;
        $this->name = $name;
        $this->identifier = $identifier;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
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
    public function getIdentifier()
    {
        return $this->identifier;
    }
}