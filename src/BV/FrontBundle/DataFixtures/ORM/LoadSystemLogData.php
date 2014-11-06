<?php

namespace BV\FrontBundle\DataFixtures\ORM;

use BV\FrontBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tools\LogBundle\Entity\SystemLog;

class LoadSystemLogData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /* @var $user_yann User */
        $user_yann = $this->getReference('user_yann');
        $user_seb = $this->getReference('user_seb');

        $syslog1 = new SystemLog(SystemLog::NOTICE);
        $syslog1->setIsRead(false);
        $syslog1->setUser($user_yann);
        $syslog1->setType(SystemLog::TYPE_USER_CREATED);

        $syslog2 = new SystemLog(SystemLog::NOTICE);
        $syslog2->setIsRead(false);
        $syslog1->setUser($user_seb);
        $syslog2->setType(SystemLog::TYPE_USER_CREATED);

        $syslog3 = new SystemLog(SystemLog::REQUIRE_ACTION);
        $syslog3->setIsRead(false);
        $syslog1->setUser($user_yann);
        $syslog3->setType(SystemLog::TYPE_USER_NEW_SEASON);

        $syslog4 = new SystemLog(SystemLog::NOTICE);
        $syslog4->setIsRead(true);
        $syslog4->setContent('PREVIOUS USER');
        $syslog4->setType(SystemLog::TYPE_USER_CREATED);

        $syslog5 = new SystemLog(SystemLog::NOTICE);
        $syslog5->setIsRead(true);
        $syslog5->setContent('ANOTHER PREVIOUS USER');
        $syslog5->setType(SystemLog::TYPE_USER_CREATED);

        $manager->persist($syslog1);
        $manager->persist($syslog2);
        $manager->persist($syslog3);
        $manager->persist($syslog4);
        $manager->persist($syslog5);

        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}
