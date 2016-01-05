<?php

namespace Bauer\IncidentDashboard\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function findByRange($identifier, \DateTime $startDate)
    {
        $this->getEntityManager()->createQueryBuilder();

        $qb = $this->createQueryBuilder('e');
        $qb->join('e.eventIdentifier', 'ei');
        $qb->where($qb->expr()->andX('ei.identifier = :identifier', $qb->expr()->gte('e.created', ':startDate')));
        $qb->orderBy('e.created', 'DESC');
        $qb->setParameter('identifier', $identifier);
        $qb->setParameter('startDate', $startDate);

        return $qb->getQuery()->getResult();
    }

    public function findFailuresSince(\DateInterval $interval, Project $project)
    {
        $this->getEntityManager()->createQueryBuilder();

        $qb = $this->createQueryBuilder('e');
        $qb->join('e.eventIdentifier', 'ei');
        $qb->where($qb->expr()->andX('e.status = :status', 'ei.project = :project', $qb->expr()->gte('e.created', ':startDate')));
        $qb->orderBy('e.created', 'DESC');

        $qb->groupBy("ei.id");

        $date = new \DateTime();
        $date->sub($interval);

        $qb->setParameter('startDate', $date);
        $qb->setParameter('status', 'failure');
        $qb->setParameter('project', $project);

        return $qb->getQuery()->getResult();
    }

    public function findRecent(Tool $tool)
    {
        $this->getEntityManager()->createQueryBuilder();

        $qb = $this->createQueryBuilder('e');
        $qb->join('e.eventIdentifier', 'ei');
        $qb->where($qb->expr()->andX('e.type = :toolIdentifier', 'ei.project = :project'));

        $qb->setParameter('toolIdentifier', $tool->getIdentifier());
        $qb->setParameter('project', $tool->getProject());

        $qb->orderBy('e.created', 'DESC');

        $results = $qb->getQuery()->getResult();

        if (count($results) == 0) {
            return null;
        } else {
            return $results[0];
        }
    }
}