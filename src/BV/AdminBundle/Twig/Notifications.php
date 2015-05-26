<?php

namespace BV\AdminBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

class Notifications extends \Twig_Extension
{
    protected $doctrine;
    protected $em;
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->doctrine = $container->get('doctrine');
        $this->em = $this->doctrine->getManager();
    }

    public function getFunctions()
    {
        return array(
            'getNotifications' => new \Twig_Function_Method($this, 'getNotifications'),
        );
    }

    public function getName()
    {
        return 'getNotifications';
    }

    public function getNotifications()
    {
        $notifications = $this->em->getRepository('ToolsLogBundle:SystemLog')->findAllOrderByLevel();
        return $notifications;
    }
}