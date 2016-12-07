<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('idci_website_configurator');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->arrayNode('websites')
                    ->defaultValue(array())
                    ->useAttributeAsKey('website_name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('alias')->defaultNull()->end()
                            ->arrayNode('theme')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('path')->defaultNull()->end()
                                ->end()
                            ->end()
                            ->arrayNode('routes')
                                ->defaultValue(array())->prototype('variable')->end()
                            ->end()
                            ->arrayNode('rules')
                                ->defaultValue(array())->prototype('variable')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
