<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\Routing\Loader;

use IDCI\Bundle\WebsiteConfiguratorBundle\RuleAssessor\RuleAssessorManager;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class WebsiteConfigurationLoader extends Loader
{
    private $loaded = false;

    /**
     * @var array $websitesConfiguration
     */
    private $websitesConfiguration;

    /**
     * @var RuleAssessorManager
     */
    private $ruleAssessorManager;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Constructor
     *
     * @param RuleAssessorManager $ruleAssessorManager
     * @param RequestStack $requestStack
     * @param array $websitesConfiguration
     */
    public function __construct(RuleAssessorManager $ruleAssessorManager, RequestStack $requestStack, $websitesConfiguration)
    {
        $this->ruleAssessorManager   = $ruleAssessorManager;
        $this->requestStack          = $requestStack;
        $this->websitesConfiguration = $websitesConfiguration;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "website configuration" loader twice');
        }

        $websiteName = $this->getMatchingWebsite();
        $routes = new RouteCollection();

        if (false !== $websiteName) {

            $websiteRouteConfiguration = $this->websitesConfiguration[$websiteName]['routes'];

            foreach ($websiteRouteConfiguration as $routeName => $config) {

                $defaults = isset($config['defaults']) ? $config['defaults'] : array();
                $requirements = isset($config['requirements']) ? $config['requirements'] : array();
                $options = isset($config['options']) ? $config['options'] : array();
                $host = isset($config['host']) ? $config['host'] : '';
                $schemes = isset($config['schemes']) ? $config['schemes'] : array();
                $methods = isset($config['methods']) ? $config['methods'] : array();
                $condition = isset($config['condition']) ? $config['condition'] : null;

                $route = new Route($config['path'], $defaults, $requirements, $options, $host, $schemes, $methods, $condition);

                // add the new route to the route collection
                $routes->add($routeName, $route);
            }
        }

        $this->loaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'website_configuration' === $type;
    }

    /**
     * Check if a website match the rules
     *
     * @return boolean
     */
    private function getMatchingWebsite()
    {
        foreach ($this->websitesConfiguration as $name => $website) {
            if ($this->match($website)) {
                return $name;
            }
        }

        return false;
    }

    /**
     * Check if the given website match the rules
     *
     * @param array $website
     *
     * @return boolean
     */
    private function match($website)
    {
        $request = $this->requestStack->getCurrentRequest();
        foreach ($website['rules'] as $rule) {
            foreach ($rule as $alias => $parameters) {
                if (!$this->ruleAssessorManager->match($alias, $request, $parameters)) {
                    return false;
                }
            }
        }

        return true;
    }
}