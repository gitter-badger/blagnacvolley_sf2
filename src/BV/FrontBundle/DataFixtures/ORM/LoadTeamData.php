<?php

namespace BV\FrontBundle\DataFixtures\ORM;

use BV\FrontBundle\Entity\User;
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
        $teamMsc1->setLevel(User::LEVEL_EXC_C);
        $teamMsc1->setSlot('Mardi 20:30 -> 23:00');
        $teamMsc1->setType(Team::TYPE_MSC);

        $teamMsc2 = new Team();
        $teamMsc2->setName('Masculin 2');
        $teamMsc2->setLevel(User::LEVEL_EXC_C);
        $teamMsc2->setSlot('Mardi 20:30 -> 23:00');
        $teamMsc2->setType(Team::TYPE_MSC);

        $teamMix1 = new Team();
        $teamMix1->setName('Mixte 1');
        $teamMix1->setLevel(User::LEVEL_HON_A);
        $teamMix1->setSlot('Mardi 20:30 -> 23:00');
        $teamMix1->setType(Team::TYPE_MIX);

        $teamFem1 = new Team();
        $teamFem1->setName('Feminin 1');
        $teamFem1->setLevel(User::LEVEL_HON_A);
        $teamFem1->setSlot('Mercredi 20:30 -> 23:00');
        $teamFem1->setType(Team::TYPE_FEM);

        $manager->persist($teamMsc1);
        $manager->persist($teamMsc2);
        $manager->persist($teamMix1);
        $manager->persist($teamFem1);
        $manager->flush();

        $this->addReference('teamMsc1', $teamMsc1);
        $this->addReference('teamMsc2', $teamMsc2);
        $this->addReference('teamMix1', $teamMix1);
        $this->addReference('teamFem1', $teamFem1);
    }

    public function getOrder()
    {
        return 1;
    }
}