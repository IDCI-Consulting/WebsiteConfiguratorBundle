<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class WebsiteCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('idci_website_configurator.website_registry') ||
            !$container->hasDefinition('idci_website_configurator.website')
        ) {
            return;
        }

        $websitesConfiguration = $container->getParameter('idci_website_configurator.configuration.websites');

        foreach ($websitesConfiguration as $name => $websiteConfiguration) {
            $this->processWebsite($container, $name, $websiteConfiguration);
        }
    }

    /**
     * Process the website services from the configuration
     *
     * @param ContainerBuilder $container
     * @param string $name
     * @param array $configuration
     *
     * @return bool
     */
    private function processWebsite(ContainerBuilder $container, $name, $configuration)
    {
        $registryDefinition = $container->getDefinition('idci_website_configurator.website_registry');
        $serviceDefinition = new DefinitionDecorator('idci_website_configurator.website');
        $serviceName = sprintf('idci_website_configurator.website.%s', $name);

        $serviceDefinition->isAbstract(false);
        $serviceDefinition->replaceArgument(0, $name);
        $serviceDefinition->replaceArgument(1, $configuration['alias']);
        $serviceDefinition->replaceArgument(2, $configuration['theme']['path']);
        $serviceDefinition->replaceArgument(3, $configuration['rules']);

        $container->setDefinition($serviceName, $serviceDefinition);

        $registryDefinition->addMethodCall(
            'setWebsite',
            array($name, new Reference($serviceName))
        );
    }
}