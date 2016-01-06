<?php

namespace Koalamon\NotificationEngineBundle\Entity;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Bauer\IncidentDashboard\CoreBundle\Entity\Tool;
use Doctrine\ORM\EntityRepository;

class NotificationConfigurationRepository extends EntityRepository
{
    public function findByTool(Project $project, Tool $tool)
    {
        $qb = $this->createQueryBuilder('nc');

        $qb->join('nc.connectedTools', 't');

        $qb->where($qb->expr()->andX('nc.project = :project', 'nc.notifyAll = true'));

        $qb->orWhere($qb->expr()->andX('nc.project = :project', 't.id = :toolId'));

        $qb->setParameter('project', $project);
        $qb->setParameter('toolId', $tool->getId());

        return $qb->getQuery()->getResult();
    }
}