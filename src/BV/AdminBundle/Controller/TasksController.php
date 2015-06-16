<?php

namespace BV\AdminBundle\Controller;

use BV\FrontBundle\Entity\User;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TasksController extends Controller
{
    public function listAction()
    {
        /* @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!$user->isSuperAdmin())
        {
            throw new AccessDeniedException();
        }

        $users = $this->container->get('doctrine')->getRepository('FrontBundle:User')->findAllActive();

        return $this->render('AdminBundle:Tasks:edit.html.twig', [
            'users' => $users
        ]);
    }
}
