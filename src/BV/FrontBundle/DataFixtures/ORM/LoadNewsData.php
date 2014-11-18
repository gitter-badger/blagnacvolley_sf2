<?php

namespace BV\FrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BV\FrontBundle\Entity\News;

class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $post = new News();
        $post->setTitle('Recherche entraîneur');

        $content = '<p>L&#39;&eacute;quipe FEM1 (niveau Excellence) recherche un coach/entra&icirc;neur b&eacute;n&eacute;vole pour superviser des entra&icirc;nements, une fois par semaine, le mercredi soir, et si possible, suivre l&#39;&eacute;quipe lors des matchs en d&eacute;placement ou &agrave; domicile.</p>

<p>Pour plus de renseignements : <a href="mailto:sandra.pizzato@laposte.net">sandra.pizzato@laposte.net</a> ou 06.12.89.62.97</p>';

        $post->setRawContent($content);
        $post->setContent($content);
        $post->setContentFormatter('richhtml');
        $post->setEnabled(true);

        $eventsVSA = $this->getReference('eventsVSA');
        $newsVS = new News();
        $newsVS->setTitle('Volley School du 11 Novembre');
        $content = "Si vous êtes disponibles, cliquez sur le lien suivant : <a href=\"#\">Ici</a>";
        $newsVS->setRawContent($content);
        $newsVS->setContent($content);
        $newsVS->setContentFormatter('richhtml');
        $newsVS->setEnabled(true);
        $newsVS->setEventsId($eventsVSA);

        $manager->persist($post);
        $manager->persist($newsVS);

        $manager->flush();

    }

    public function getOrder()
    {
        return 4;
    }
}