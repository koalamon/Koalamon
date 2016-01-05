<?php

namespace Bauer\IncidentDashboard\CoreBundle\DataFixtures\ORM;

use Bauer\IncidentDashboard\CoreBundle\Entity\System;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Bauer\IncidentDashboard\CoreBundle\Entity\User;
use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LoadSystemData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * @var Finder
     */
    private $fixtures;

    public function __construct()
    {
        $this->fixtures = Finder::create()
            ->files()
            ->name('system_*.json')
            ->in(__DIR__ . '/../../Resources/fixtures');
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->fixtures as $file) {
            /** @var SplFileInfo $file */
            $fixture = json_decode($file->getContents());
            $system = new System();
            $system->setIdentifier($fixture->identifier);
            $system->setName($fixture->name);
            $system->setUrl($fixture->url);
            /** @var Project $project */
            $project = $this->getReference('project-' . $fixture->project);
            $system->setProject($project);

            $manager->persist($system);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            'Bauer\IncidentDashboard\CoreBundle\DataFixtures\ORM\LoadProjectData',
        ];
    }
}
