<?php

namespace Koalamon\ConsoleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ScreenshotCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('koalamon:screenshots:create')
            ->setDescription('creates the screenshots of all systems.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $systems = $em->getRepository('BauerIncidentDashboardCoreBundle:System')->findAll();

        foreach ($systems as $system) {

            if ($system->getUrl()) {

                $imageName = time() . rand(1, 100000000) . '.png';

                $imageDir = __DIR__ . "/../../../../htdocs/images/screenshots/";

                $command = "timeout 30s /root/phantomjs/bin/phantomjs "
                    . __DIR__ . "/screenshot.js "
                    . $system->getUrl() . " "
                    . $imageDir . $imageName
                    . " 1366px*2000px";

                $output->writeln("Creating screenshot for " . $system->getUrl());

                exec($command, $commandOutput, $commandStatus);

                if ($commandStatus == 0) {
                    if ($system->getImage() && file_exists($imageDir . $system->getImage())) {
                        unlink($imageDir . $system->getImage());
                    }

                    $system->setImage($imageName);
                    $em->persist($system);
                    $em->flush();
                }
            }
        }
    }
}
