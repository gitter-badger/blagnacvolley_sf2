<?php

namespace Application\Sonata\NewsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\NewsBundle\Entity\Post;
use Sonata\NewsBundle\Model\Comment;

class LoadNewsData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $post = new Post();
        $post->setTitle('Recherche entraîneur');
        $post->setAbstract('');

        $content = "L'équipe FEM1 (niveau Excellence) recherche un coach/entraîneur bénévole pour superviser des entraînements, une fois par semaine, le mercredi soir, et si possible, suivre l'équipe lors des matchs en déplacement ou à domicile.
Pour plus de renseignements : sandra.pizzato@laposte.net ou 06.12.89.62.97";

        $post->setRawContent($content);
        $post->setContent($content);
        $post->setContentFormatter('raw');
        $post->setEnabled(true);
        $post->setCommentsDefaultStatus(Comment::STATUS_INVALID);

        $manager->persist($post);
        $manager->flush();
    }
} 