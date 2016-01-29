<?php

namespace Koalamon\DefaultBundle\Menu;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Symfony\Component\Routing\Router;

class Menu
{
    private $elements = array();

    /**
     * Menu constructor.
     */
    public function addElement(Element $element)
    {
        $this->elements[$element->getIdentifier()] = $element;
    }

    public function getElements()
    {
        return $this->elements;
    }
}