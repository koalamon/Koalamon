<?php

namespace Bauer\IncidentDashboard\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EventIdentifierRepository extends EntityRepository
{
    public function findNewest(Project $project)
    {
        $qb = $this->createQueryBuilder('ei');
        $qb->join('ei.lastEvent', 'e');
        $qb->where('ei.project = :project');
        $qb->orderBy("e.id", "DESC");

        $qb->setParameter('project', $project);

        return $qb->getQuery()->getResult();
    }
}