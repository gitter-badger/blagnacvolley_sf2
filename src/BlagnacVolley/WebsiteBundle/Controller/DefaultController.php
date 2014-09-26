<?php

namespace BlagnacVolley\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BlagnacVolleyWebsiteBundle:Default:index.html.twig', array());
    }
}
