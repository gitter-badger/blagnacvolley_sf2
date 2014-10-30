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

        $manager->persist($cmsPageVS);

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
