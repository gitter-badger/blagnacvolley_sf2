<?php

namespace BV\FrontBundle\DataFixtures\ORM;

use BV\FrontBundle\Entity\CmsPage;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCmsPageData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Volley School
        $cmsPageVS = new CmsPage();
        $cmsPageVS->setContent('');
        $cmsPageVS->setName(CmsPage::STATIC_PAGE_VOLLEYSCHOOL);
        $cmsPageVS->setCreatedAt(new \DateTime());
        $cmsPageVS->setUpdatedAt(null);
        $cmsPageVS->setDescription("Ecole de volley");

        // Volley School Adults
        $cmsPageVSA = new CmsPage();
        $cmsPageVSA->setContent('');
        $cmsPageVSA->setName(CmsPage::STATIC_PAGE_VOLLEYSCHOOL_ADULTS);
        $cmsPageVSA->setCreatedAt(new \DateTime());
        $cmsPageVSA->setUpdatedAt(null);
        $cmsPageVSA->setDescription("Ecole de volley Adultes");

        // Volley School Youth
        $cmsPageVSY = new CmsPage();
        $cmsPageVSY->setContent('');
        $cmsPageVSY->setName(CmsPage::STATIC_PAGE_VOLLEYSCHOOL_YOUTH);
        $cmsPageVSY->setCreatedAt(new \DateTime());
        $cmsPageVSY->setUpdatedAt(null);
        $cmsPageVSY->setDescription("Ecole de volley Jeunes");

        // Jeu libre
        $cmsPageFG = new CmsPage();
        $cmsPageFG->setContent('');
        $cmsPageFG->setName(CmsPage::STATIC_PAGE_FREE_GAME);
        $cmsPageFG->setCreatedAt(new \DateTime());
        $cmsPageFG->setUpdatedAt(null);
        $cmsPageFG->setDescription("Jeu libre");

        // Horaires
        $cmsPageS = new CmsPage();
        $cmsPageS->setContent('');
        $cmsPageS->setName(CmsPage::STATIC_PAGE_SCHEDULE);
        $cmsPageS->setCreatedAt(new \DateTime());
        $cmsPageS->setUpdatedAt(null);
        $cmsPageS->setDescription("Horaires");

        // Addresses
        $cmsPageA = new CmsPage();
        $cmsPageA->setContent('');
        $cmsPageA->setName(CmsPage::STATIC_PAGE_ADDRESSES);
        $cmsPageA->setCreatedAt(new \DateTime());
        $cmsPageA->setUpdatedAt(null);
        $cmsPageA->setDescription("Adresses");

        $manager->persist($cmsPageVS);
        $manager->persist($cmsPageVSA);
        $manager->persist($cmsPageVSY);
        $manager->persist($cmsPageFG);
        $manager->persist($cmsPageS);
        $manager->persist($cmsPageA);

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
