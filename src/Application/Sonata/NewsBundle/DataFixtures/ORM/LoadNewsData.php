<?php

namespace Application\Sonata\NewsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\NewsBundle\Entity\Post;
use Sonata\NewsBundle\Model\Comment;

class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $post = new Post();

        $title = 'Recherche entraîneur';
        $post->setTitle($title);
        $post->setAbstract($title);
        $post->setAuthor($this->getReference('admin-user'));

        $content = '<p>L&#39;&eacute;quipe FEM1 (niveau Excellence) recherche un coach/entra&icirc;neur b&eacute;n&eacute;vole pour superviser des entra&icirc;nements, une fois par semaine, le mercredi soir, et si possible, suivre l&#39;&eacute;quipe lors des matchs en d&eacute;placement ou &agrave; domicile.</p>

<p>Pour plus de renseignements : <a href="mailto:sandra.pizzato@laposte.net">sandra.pizzato@laposte.net</a> ou 06.12.89.62.97</p>';

        $post->setRawContent($content);
        $post->setContent($content);
        $post->setContentFormatter('richhtml');
        $post->setEnabled(true);

        $post->setCommentsDefaultStatus(Comment::STATUS_INVALID);
        $post->setCommentsEnabled(false);

        $manager->persist($post);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
} 