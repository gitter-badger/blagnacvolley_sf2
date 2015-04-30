<?php

namespace BV\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CustomController extends Controller
{
    public function dashboardAction(Request $request)
    {
        return $this->render("AdminBundle:Core:dashboard.html.twig", array(
            'base_template'   => "AdminBundle::layout.html.twig",
            'admin_pool'      => $this->container->get('sonata.admin.pool'),
            'users'           => $this->container->get('doctrine')->getRepository('FrontBundle:User')->countUsersForDashboard(),
            'notifications'   => $this->container->get('doctrine')->getRepository('ToolsLogBundle:SystemLog')->findAllOrderByLevel()
        ));
    }


}
