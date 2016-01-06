<?php

namespace Koalamon\InformationBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\DateTime;

class InformationRepository extends EntityRepository
{
    public function findCurrentInformation(Project $project)
    {
        $qb = $this->createQueryBuilder('i');
        $qb->where("i.project = :project");
        $qb->andWhere("i.endDate >= :endDate");

        $qb->orderBy('i.id', 'ASC');

        $qb->setParameter('project', $project);
        $qb->setParameter('endDate', time());

        return $qb->getQuery()->getResult();
    }
}
