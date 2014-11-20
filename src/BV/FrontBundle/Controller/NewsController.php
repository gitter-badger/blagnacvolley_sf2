<?php

namespace BV\FrontBundle\Controller;

use BV\FrontBundle\Entity\Events;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsController extends Controller
{
    public function displayActivesAction()
    {
        $repository = $this->getDoctrine()->getRepository('BV\FrontBundle\Entity\News');

        $query = $repository->createQueryBuilder('n')
            ->where('n.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('n.createdAt')
            ->getQuery();

        $news = $query->getResult();

        return $this->render('FrontBundle:News:displayActive.html.twig', array(
                'news' => $news,
        ));
    }

    public function displayDoodlesAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $nb = $this->getDoctrine()->getRepository('FrontBundle:User')->countUsersByGroups();
        $repository = $this->getDoctrine()->getRepository('BV\FrontBundle\Entity\Events');
        $qb = $repository->createQueryBuilder('n');

        $qb->where(
            $qb->expr()->andX(
                $qb->expr()->gt('n.startDate', ':startDate'),
                $qb->expr()->in('n.type', ':type')
            ))
            ->setParameter('startDate', new \DateTime())
            ->setParameter('type', array(Events::TYPE_VOLLEYSCHOOL_ADULT, Events::TYPE_VOLLEYSCHOOL_YOUTH, Events::TYPE_FREE_PLAY))
            ->orderBy('n.startDate')
            ->getQuery();

        $doodles = $qb->getQuery()->getResult();

        $res = array(
            Events::TYPE_VOLLEYSCHOOL_ADULT => array('event' => null, 'availabilities' => null),
            Events::TYPE_VOLLEYSCHOOL_YOUTH => array('event' => null, 'availabilities' => null),
            Events::TYPE_FREE_PLAY => array('event' => null, 'availabilities' => null),
        );

        foreach ($doodles as $event)
        {
            /* @var $event Events */

            if ($res[$event->getType()]['event'] != null)
                continue;

            $res[$event->getType()]['event'] = $event;
            $res[$event->getType()]['availabilities'] = $this->getDoctrine()->getRepository('FrontBundle:Availability')->countAvailabilities($event);
            $res[$event->getType()]['availability'] = $this->getDoctrine()->getRepository('FrontBundle:Availability')->findByUserAndEvent($user, $event);
        }

        return $this->render('FrontBundle:News:displayDoodles.html.twig', array(
            'events' => $res,
            'nb' => $nb,
            'user' => $user,
        ));
    }
}
