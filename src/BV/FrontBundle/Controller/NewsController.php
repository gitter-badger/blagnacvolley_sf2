<?php

namespace BV\FrontBundle\Controller;

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

}
