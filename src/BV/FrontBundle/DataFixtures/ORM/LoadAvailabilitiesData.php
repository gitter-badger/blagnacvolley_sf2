<?php

namespace BV\FrontBundle\DataFixtures\ORM;

use BV\FrontBundle\Entity\Availability;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BV\FrontBundle\Entity\News;

class LoadAvailabilitiesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $eventsVSA = $this->getReference('eventsVSA');
        $user_yann = $this->getReference('user_yann');
        $user_seb = $this->getReference('user_seb');
        $user_1 = $this->getReference('user_1');

        $availability_yann = new Availability();
        $availability_yann->setUser($user_yann);
        $availability_yann->setEvents($eventsVSA);
        $availability_yann->setIsAvailable(true);
        $availability_yann->setValidatedAt(new \Datetime());

//        $availability_seb = new Availability();
//        $availability_seb->setUser($user_seb);
//        $availability_seb->setEvents($eventsVSA);
//        $availability_seb->setIsAvailable(null);
//        $availability_seb->setValidatedAt(null);

        $availability_user1 = new Availability();
        $availability_user1->setUser($user_1);
        $availability_user1->setEvents($eventsVSA);
        $availability_user1->setIsAvailable(false);
        $availability_user1->setValidatedAt(new \Datetime());

        $manager->persist($availability_yann);
//        $manager->persist($availability_seb);
        $manager->persist($availability_user1);

        $manager->flush();

    }

    public function getOrder()
    {
        return 5;
    }
}