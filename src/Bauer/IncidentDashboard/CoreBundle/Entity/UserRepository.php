<?php

namespace Bauer\IncidentDashboard\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class UserRepository extends EntityRepository
{
    /**
     * @return Event[]
     */
    public function findByUsername($startsWith)
    {
        $this->getEntityManager()->createQueryBuilder();

        $qb = $this->createQueryBuilder('u');
        $qb->where("u.username like :startsWith");
        $qb->orderBy('u.username', 'DESC');
        $qb->setParameter('startsWith', $startsWith . "%");

        return $qb->getQuery()->getResult();
    }
}