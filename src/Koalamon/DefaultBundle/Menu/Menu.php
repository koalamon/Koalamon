<?php

namespace Koalamon\DefaultBundle\Menu;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Symfony\Component\Routing\Router;

class Menu
{
    private $elements = array();

    /**
     * @var Element
     */
    private $selected;

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

    public function setSelected(Element $element)
    {
        $this->selected = $element;
    }

    public function isSelected(Element $element)
    {
        return $element == $this->selected;
    }
}