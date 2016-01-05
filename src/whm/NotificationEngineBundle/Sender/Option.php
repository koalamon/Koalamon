<?php

namespace whm\NotificationEngineBundle\Sender;

class Option
{
    private $label;
    private $identifier;
    private $description;
    private $type;
    private $mandatory;

    /**
     * Option constructor.
     *
     * @param $label
     * @param $identifier
     * @param $description
     */
    public function __construct($label, $identifier, $description, $type, $mandatory = false)
    {
        $this->label = $label;
        $this->identifier = $identifier;
        $this->description = $description;
        $this->type = $type;
        $this->mandatory = $mandatory;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
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
    public function getType()
    {
        return $this->type;
    }

    public function isMandatory()
    {
        return $this->mandatory;
    }
}