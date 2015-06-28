<?php

namespace BV\FrontBundle\Controller;


use BV\FrontBundle\Entity\User;
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
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        $entities['fem'] = $em->getRepository('FrontBundle:Team')->findBy(array('type' => Team::TYPE_FEM));
        $entities['msc'] = $em->getRepository('FrontBundle:Team')->findBy(array('type' => Team::TYPE_MSC));
        $entities['mix'] = $em->getRepository('FrontBundle:Team')->findBy(array('type' => Team::TYPE_MIX));

        return $this->render('FrontBundle:Team:index.html.twig', array(
            'entities' => $entities,
            'allowed_to_view' => ( $user instanceof User && $user->getStatus() == User::STATUS_ACTIVE_LICENSED)
        ));
    }

    /**
     * Finds and displays a Team entity.
     *
     */
    public function showAction($id)
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();
        $allowed = ( $user instanceof User && $user->getStatus() == User::STATUS_ACTIVE_LICENSED);

        if (!$allowed) {
            $this->container->get('session')->getFlashBag()->add('error', 'Vous n\'êtes pas autorisé à faire cela');
            return $this->redirect($this->generateUrl('teams'));
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontBundle:Team')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        return $this->render('FrontBundle:Team:show.html.twig', array(
            'team'      => $entity,
            'users'     => $em->getRepository('FrontBundle:User')->findAllByTeam($id)
        ));
    }
}
