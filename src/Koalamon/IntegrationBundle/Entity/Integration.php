<?php

namespace Koalamon\Integration\KoalaPingBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Entity\System;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Integration
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

}
