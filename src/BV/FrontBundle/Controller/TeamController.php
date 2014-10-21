<?php

namespace BV\FrontBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BV\FrontBundle\Entity\Team;

/**
 * Team controller.
 *
 */
class TeamController extends Controller
{

    /**
     * Lists all Team entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FrontBundle:Team')->findAll();

        return $this->render('FrontBundle:Team:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Team entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontBundle:Team')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        return $this->render('FrontBundle:Team:show.html.twig', array(
            'entity'      => $entity,
        ));
    }
}
