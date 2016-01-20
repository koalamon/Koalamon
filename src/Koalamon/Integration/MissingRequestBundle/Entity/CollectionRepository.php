<?php

namespace Koalamon\Integration\MissingRequestBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Entity\System;
use Doctrine\ORM\EntityRepository;

class CollectionRepository extends EntityRepository
{
    public function findBySystem(System $system)
    {
        $qb = $this->createQueryBuilder("c")
            ->where(':system MEMBER OF c.systems')
            ->setParameter('system', $system);
        return $qb->getQuery()->getResult();
    }
}
