<?php

namespace BV\FrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use BV\FrontBundle\Entity\Events;

class LoadEventsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Load Vacations
        $events = new Events();
        $events->setStartDate(new \DateTime('2014-10-20 00:00:00'));
        $events->setEndDate(new \DateTime('2014-11-02 23:59:59'));
        $events->setType(Events::TYPE_CLOSED);
        $events->setText("Vacances Toussaint");

        $manager->persist($events);

        $teamMsc1 = $this->getReference('teamMsc1');
        $teamMix1 = $this->getReference('teamMix1');
        $teamFem1 = $this->getReference('teamFem1');

        // Tuesday Training
        $eventsMsc1 = new Events();
        $eventsMsc1->setStartDate(new \DateTime('2014-10-14 20:30:00'));
        $eventsMsc1->setEndDate(new \DateTime('2014-10-14 23:00:00'));
        $eventsMsc1->setType(Events::TYPE_TRAINING);
        $eventsMsc1->setTeam($teamMsc1);

        $eventsMix1 = new Events();
        $eventsMix1->setStartDate(new \DateTime('2014-10-14 20:30:00'));
        $eventsMix1->setEndDate(new \DateTime('2014-10-14 23:00:00'));
        $eventsMix1->setType(Events::TYPE_TRAINING);
        $eventsMix1->setTeam($teamMix1);

        $eventsFem1 = new Events();
        $eventsFem1->setStartDate(new \DateTime('2014-10-14 20:30:00'));
        $eventsFem1->setEndDate(new \DateTime('2014-10-14 23:00:00'));
        $eventsFem1->setType(Events::TYPE_TRAINING);
        $eventsFem1->setTeam($teamFem1);

        $manager->persist($events);
        $manager->persist($eventsMsc1);
        $manager->persist($eventsMix1);
        $manager->persist($eventsFem1);

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}