<?php

namespace Bauer\IncidentDashboard\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Bauer\IncidentDashboard\CoreBundle\Entity\User;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LoadUserData extends AbstractFixture implements FixtureInterface
{
    /**
     * @var Finder
     */
    private $fixtures;

    public function __construct()
    {
        $this->fixtures = Finder::create()
            ->files()
            ->name('user_*.json')
            ->in(__DIR__ . '/../../Resources/fixtures');
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->fixtures as $file) {
            /** @var SplFileInfo $file */
            $fixture = json_decode($file->getContents());
            $user = new User();
            $user->setUsername($fixture->username)
                ->setPlainPassword($fixture->password)
                ->setEmail($fixture->email);

            if (isset($fixture->facebookId)) {
                $user->setFacebookId($fixture->facebookId);
            }
            if (isset($fixture->githubId)) {
                $user->setGithubId($fixture->githubId);
            }
//            if (isset($fixture->projects)) {
            //                foreach ($)
            //}
            $manager->persist($user);

            $this->addReference('user-' . $user->getUsernameCanonical(), $user);
        }

        $manager->flush();
    }
}
