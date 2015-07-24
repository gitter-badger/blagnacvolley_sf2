<?php

namespace BV\FrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use BV\FrontBundle\Entity\User;

class LoadAdminData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setSuperAdmin(true);
        $userAdmin->setEnabled(true);
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('blagnacvolley@gmail.com');
        $userAdmin->setPlainPassword('pass4bv');
        $userAdmin->setFirstname('Admin');
        $userAdmin->setLastname('Admin');
        $userAdmin->setGender('N/A');
        // déclaration de l'association en préfecture ;-)
        $userAdmin->setDob(new \DateTime('2004-03-30'));
        // gymnase guillaumet
        $userAdmin->setAddress('20 Chemin de Belisaire, 31700 Blagnac');
        $userAdmin->setPicture('/images/user_default.png');
        $userAdmin->setGeoLat('43.6334661');
        $userAdmin->setGeoLng('1.3874611');
        $userAdmin->setIsVolleySchoolAdult(false);
        $userAdmin->setIsVolleySchoolYouth(false);
        $userAdmin->setIsFreeplay(false);
        $userAdmin->setIsSubscribedInsurance(false);

        $manager->persist($userAdmin);
        $manager->flush();

        $this->addReference('user_admin', $userAdmin);
    }

    public function getOrder()
    {
        return 0;
    }
}