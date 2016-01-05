<?php
/**
 * Created by IntelliJ IDEA.
 * User: jan.schumann
 * Date: 01.12.15
 * Time: 11:03
 */

namespace Koalamon\ConsoleBundle\Command;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Bauer\IncidentDashboard\CoreBundle\Entity\System;
use Bauer\IncidentDashboard\CoreBundle\Entity\Tool;
use Bauer\IncidentDashboard\CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class GenerateFixturesCommand extends ContainerAwareCommand
{
    private $fixturePath;

    protected function configure()
    {
        $this
            ->setName('koalamon:fixtures:generate')
            ->setDescription('generate fixtures from existing database.');

        $this->fixturePath = __DIR__ . '/../../../Bauer/IncidentDashboard/CoreBundle/Resources/fixtures';
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $tools = $em->getRepository('BauerIncidentDashboardCoreBundle:Tool')->findAll();

        /** @var Tool $tool */
        foreach ($tools as $tool) {
            $fixture = $tool->jsonSerialize();

            file_put_contents($this->fixturePath . '/tool_' . $tool->getId() . '.json', json_encode($fixture, JSON_PRETTY_PRINT));
        }

        $systems = $em->getRepository('BauerIncidentDashboardCoreBundle:System')->findAll();

        /** @var System $system */
        foreach ($systems as $system) {
            $fixture = $system->jsonSerialize();

            file_put_contents($this->fixturePath . '/system_' . $system->getId() . '.json', json_encode($fixture, JSON_PRETTY_PRINT));
        }

        $projects = $em->getRepository('BauerIncidentDashboardCoreBundle:Project')->findAll();

        /** @var Project $project */
        foreach ($projects as $project) {
            $fixture = $project->jsonSerialize();

            file_put_contents($this->fixturePath . '/project_' . $project->getId() . '.json', json_encode($fixture, JSON_PRETTY_PRINT));
        }

        $users = $em->getRepository('BauerIncidentDashboardCoreBundle:User')->findAll();

        /** @var User $user */
        foreach ($users as $user) {
            $fixture = $user->jsonSerialize();
            $fixture->password = 'master';

            file_put_contents($this->fixturePath . '/user_' . $user->getId() . '.json', json_encode($fixture, JSON_PRETTY_PRINT));
        }
    }
}
