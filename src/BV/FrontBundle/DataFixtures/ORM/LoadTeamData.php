<?php

namespace BV\FrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use BV\FrontBundle\Entity\Team;

class LoadTeamData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $teamMsc1 = new Team();
        $teamMsc1->setName('Masculin 1');
        $teamMsc1->setLevel('Excellence C');
        $teamMsc1->setSlot('Mardi 20:30 -> 23:00');
        $teamMsc1->setType('Masculin');

        $teamMix1 = new Team();
        $teamMix1->setName('Mixte 1');
        $teamMix1->setLevel('Honneur A');
        $teamMix1->setSlot('Mardi 20:30 -> 23:00');
        $teamMix1->setType('Mixte');

        $teamFem1 = new Team();
        $teamFem1->setName('Feminin 1');
        $teamFem1->setLevel('Honneur A');
        $teamFem1->setSlot('Mercredi 20:30 -> 23:00');
        $teamFem1->setType('Feminin');

        $manager->persist($teamMsc1);
        $manager->persist($teamMix1);
        $manager->persist($teamFem1);
        $manager->flush();

        $this->addReference('teamMsc1', $teamMsc1);
        $this->addReference('teamMix1', $teamMix1);
        $this->addReference('teamFem1', $teamFem1);
    }

    public function getOrder()
    {
        return 1;
    }
}