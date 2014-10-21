<?php

namespace BV\FrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use BV\FrontBundle\Entity\User;
use BV\FrontBundle\Entity\Team;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user_yann = new User();
        $user_yann->setFirstname('Yann');
        $user_yann->setLastname('Lastapis');
        $user_yann->setLevel('Excellence C');
        $user_yann->setAddress('5 rue du gorp, apt 26, 31400 Toulouse');
        $date = new \DateTime('2014-09-30');
        $user_yann->setDatePayment($date);
        $date = new \DateTime('2014-01-01');
        $user_yann->setDateShirtDelivered($date);
        $date = new \DateTime('1983-04-04');
        $user_yann->setDob($date);
        $user_yann->setFeeAmount('65');
        $user_yann->setFemTeam(null);
        $user_yann->setMscTeam(null);
        $user_yann->setMixTeam(null);
        $user_yann->setGender('H');
        $user_yann->setGeoLat('43.587638');
        $user_yann->setGeoLng('1.448412');
        $user_yann->setPicture('EMPTY');
        $user_yann->setShirtSize('L');
        $user_yann->setIsRequiredBill(false);
        $user_yann->setIsLookingForTeam(false);
        $user_yann->setStatus('ACTIVE_LICENCED');
        $user_yann->setLicenceNumber('3216613211681');
        // base User implementation
        $user_yann->setUsername('shazim');
        $user_yann->setPlainPassword('pass4bv');
        $user_yann->setEmail('y.lastapis@gmail.com');
        $user_yann->setEnabled(true);

        $user_seb = new User();
        $user_seb->setFirstname('Sébastien');
        $user_seb->setLastname('Fastrez');
        $user_seb->setLevel('Excellence C');
        $user_seb->setAddress('14 impasse de Cornaudric , 31240 l\'Union');
        $date = new \DateTime('2014-09-30');
        $user_seb->setDatePayment($date);
        $date = new \DateTime('2014-01-01');
        $user_seb->setDateShirtDelivered($date);
        $date = new \DateTime('1983-04-21');
        $user_seb->setDob($date);
        $user_seb->setFeeAmount('55');
        $user_seb->setFemTeam(null);
        $user_seb->setMscTeam(null);
        $user_seb->setMixTeam(null);
        $user_seb->setGender('H');
        $user_seb->setGeoLat('43.662233');
        $user_seb->setGeoLng('1.476035');
        $user_seb->setPicture('EMPTY');
        $user_seb->setShirtSize('L');
        $user_seb->setIsRequiredBill(false);
        $user_seb->setIsLookingForTeam(false);
        $user_seb->setStatus('ACTIVE_LICENCED');
        $user_seb->setLicenceNumber('132132151');
        // base User implementation
        $user_seb->setUsername('perko');
        $user_seb->setPlainPassword('pass4bv');
        $user_seb->setEmail('s.fastrez@gmail.com');
        $user_seb->setEnabled(true);

        /* @var $teamMsc1 Team */
        $teamMsc1 = $this->getReference('teamMsc1');

        $user_yann->setMscTeam($teamMsc1);
        $teamMsc1->setCaptain($user_yann);

        $user_seb->setMscTeam($teamMsc1);
        $teamMsc1->setSubCaptain($user_seb);

        $manager->persist($user_yann);
        $manager->persist($user_seb);
        $manager->flush();

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}