<?php

namespace BV\FrontBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FrontExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('front.web_dir', $config['web_dir']);
        $container->setParameter('front.profile.pictures_path', $config['profile']['pictures_path']);
        $container->setParameter('front.profile.certif_path', $config['profile']['certif_path']);
        $container->setParameter('front.profile.attestation_path', $config['profile']['attestation_path']);
        $container->setParameter('front.profile.parental_advisory_path', $config['profile']['parental_advisory_path']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
