<?php

namespace BV\FrontBundle\DataFixtures\ORM;

use BV\FrontBundle\Entity\Email;
use BV\FrontBundle\Services\Email as EmailService;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadEmailData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Certificat refusé
        $email_1 = new Email();
        $email_1->setTitle("[BlagnacVolley] l'admin a refusé votre certificat");
        $email_1->setContent(file_get_contents(dirname(__FILE__).'/sql/'.EmailService::TYPE_SEND_CERTIF_REFUSED_EMAIL.'.txt'));
        $email_1->setName(EmailService::TYPE_SEND_CERTIF_REFUSED_EMAIL);
        $email_1->setCreatedAt(new \DateTime());
        $email_1->setUpdatedAt(null);

        $manager->persist($email_1);

        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}
