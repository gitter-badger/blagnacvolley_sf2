<?php

namespace BV\FrontBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

class Footer extends \Twig_Extension
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
            'getBureau' => new \Twig_Function_Method($this, 'getBureau'),
            'getCapitaines' => new \Twig_Function_Method($this, 'getCapitaines'),
        );
    }

    public function getName()
    {
        return 'getFooter';
    }

    public function getBureau()
    {
        $listPacks = $this->em->getRepository('FrontBundle:User')->getDeskUsers();
        return $listPacks;
    }

    public function getCapitaines()
    {
        $listPacks = $this->em->getRepository('FrontBundle:Team')->getCapitaines();
        return $listPacks;
    }
}