<?php

namespace BlagnacVolley\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BlagnacVolleyWebsiteBundle:Default:index.html.twig', array());
    }

    public function staticAction($page)
    {
        $tpl = ':static:'.$page.'.html.twig';
        if ($this->container->get('templating')->exists($tpl)) {
            $response = $this->render($tpl, array());
        } else {
            throw $this->createNotFoundException('Page not found');
        }
        return $response;
    }
}
