<?php

namespace BV\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FrontBundle:Default:index.html.twig', array());
    }

    public function volleySchoolAction()
    {
        return $this->render('FrontBundle:Default:volleyschool.html.twig', array());
    }
}
