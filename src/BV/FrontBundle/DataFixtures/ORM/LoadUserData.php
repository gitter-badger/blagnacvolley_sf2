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
        $user_yann->setLevel(User::LEVEL_EXC_C);
        $user_yann->setPoste(User::POSTE_POINTU);
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
        $user_yann->setGender(User::GENDER_MALE);
        $user_yann->setGeoLat('43.587638');
        $user_yann->setGeoLng('1.448412');
        $user_yann->setPicture('/uploads/users/15.jpg');
        $user_yann->setShirtSize('L');
        $user_yann->setIsRequiredBill(false);
        $user_yann->setIsLookingForTeam(false);
        $user_yann->setStatus(USER::STATUS_ACTIVE_LICENSED);
        $user_yann->setLicenseNumber('3216613211681');
        // base User implementation
        $user_yann->setUsername('shazim');
        $user_yann->setPlainPassword('pass4bv');
        $user_yann->setEmail('y.lastapis@gmail.com');
        $user_yann->setEnabled(true);
        $user_yann->setIsVolleySchoolAdult(true);
        $user_yann->setIsVolleySchoolYouth(false);
        $user_yann->setIsFreeplay(false);

        $user_seb = new User();
        $user_seb->setFirstname('Sébastien');
        $user_seb->setLastname('Fastrez');
        $user_seb->setLevel(User::LEVEL_EXC_C);
        $user_seb->setPoste(User::POSTE_CENTRAL);
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
        $user_seb->setGender(User::GENDER_MALE);
        $user_seb->setGeoLat('43.662233');
        $user_seb->setGeoLng('1.476035');
        $user_seb->setPicture('/images/defaults/default_male.png');
        $user_seb->setShirtSize('L');
        $user_seb->setIsRequiredBill(false);
        $user_seb->setIsLookingForTeam(false);
        $user_seb->setStatus(USER::STATUS_ACTIVE_LICENSED);
        $user_seb->setLicenseNumber('132132151');
        // base User implementation
        $user_seb->setUsername('perko');
        $user_seb->setPlainPassword('pass4bv');
        $user_seb->setEmail('s.fastrez@gmail.com');
        $user_seb->setEnabled(true);
        $user_seb->setIsVolleySchoolAdult(false);
        $user_seb->setIsVolleySchoolYouth(true);
        $user_seb->setIsFreeplay(false);

        $user1 = new User();
        $user1->setFirstname('Jean-Yves');
        $user1->setLastname('PICHON');
        $user1->setLevel(User::LEVEL_EXC_C);
        $user1->setPoste(User::POSTE_RECEP4);
        $user1->setAddress('14 impasse de Cornaudric , 31240 l\'Union');
        $date = new \DateTime('2014-09-30');
        $user1->setDatePayment($date);
        $date = new \DateTime('2014-01-01');
        $user1->setDateShirtDelivered($date);
        $date = new \DateTime('1983-04-21');
        $user1->setDob($date);
        $user1->setFeeAmount('55');
        $user1->setFemTeam(null);
        $user1->setMscTeam(null);
        $user1->setMixTeam(null);
        $user1->setGender(User::GENDER_MALE);
        $user1->setGeoLat('43.662233');
        $user1->setGeoLng('1.476035');
        $user1->setPicture('/images/defaults/default_male.png');
        $user1->setCertif('/uploads/certif/pichon.png');
        $user1->setShirtSize('L');
        $user1->setIsRequiredBill(false);
        $user1->setIsLookingForTeam(false);
        $user1->setStatus(USER::STATUS_ACTIVE_NOT_LICENSED);
        $user1->setLicenseNumber('132132151');
        // base User implementation
        $user1->setUsername('pichon');
        $user1->setPlainPassword('pass4bv');
        $user1->setEmail('jy.pichon@gmail.com');
        $user1->setEnabled(true);
        $user1->setIsVolleySchoolAdult(false);
        $user1->setIsVolleySchoolYouth(false);
        $user1->setIsFreeplay(true);

        /* @var $teamMsc1 Team */
        $teamMsc1 = $this->getReference('teamMsc1');

        $user_yann->setMscTeam($teamMsc1);
        $teamMsc1->setCaptain($user_yann);

        $user_seb->setMscTeam($teamMsc1);
        $teamMsc1->setSubCaptain($user_seb);

        $user1->setMscTeam($teamMsc1);

        $manager->persist($user_yann);
        $manager->persist($user_seb);
        $manager->persist($user1);

        $this->addReference('user_yann', $user_yann);
        $this->addReference('user_seb', $user_seb);
        $this->addReference('user_1', $user1);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}