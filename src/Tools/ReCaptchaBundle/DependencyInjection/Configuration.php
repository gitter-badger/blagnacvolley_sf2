<?php

namespace Tools\ReCaptchaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('re_captcha');

        $rootNode
            ->children()
                ->scalarNode('url')->cannotBeEmpty()->defaultValue('https://www.google.com/recaptcha/api/siteverify')->end()
                ->scalarNode('secret')->cannotBeEmpty()->defaultValue('YourGoogleReCaptchaSecret')->end()
                ->scalarNode('public_key')->cannotBeEmpty()->defaultValue('YourGoogleReCaptchaPublicKey')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
