<?php

namespace BlagnacVolley\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TeamBundle:Default:index.html.twig', array('name' => $name));
    }

    public function listAction()
    {
        return $this->render('TeamBundle:Default:list.html.twig');
    }
}
