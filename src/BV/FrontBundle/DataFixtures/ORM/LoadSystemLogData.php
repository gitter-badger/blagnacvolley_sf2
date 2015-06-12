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

        $syslog1 = new SystemLog(SystemLog::NOTICE);
        $syslog1->setIsRead(false);
        $syslog1->setUser($user_yann);
        $syslog1->setType(SystemLog::TYPE_USER_CREATED);

        $manager->persist($syslog1);

        $syslog2 = new SystemLog(SystemLog::REQUIRE_ACTION);
        $syslog2->setIsRead(false);
        $syslog2->setUser($user_yann);
        $syslog2->setType(SystemLog::TYPE_USER_WAITING_VALIDATION);

        $manager->persist($syslog2);

        $syslog3 = new SystemLog(SystemLog::NOTICE);
        $syslog3->setIsRead(false);
        $syslog3->setUser($user_yann);
        $syslog3->setType(SystemLog::TYPE_USER_ACCOUNT_DELETED);

        $manager->persist($syslog3);

        $syslog4 = new SystemLog(SystemLog::REQUIRE_ACTION);
        $syslog4->setIsRead(false);
        $syslog4->setUser($user_yann);
        $syslog4->setType(SystemLog::TYPE_USER_INFORMATIONS_CHANGED);

        $manager->persist($syslog4);

        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}
