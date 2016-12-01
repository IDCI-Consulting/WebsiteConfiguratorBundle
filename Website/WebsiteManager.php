<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\Website;

use Symfony\Component\HttpFoundation\Request;
use IDCI\Bundle\WebsiteConfiguratorBundle\Exception\UnexpectedTypeException;
use IDCI\Bundle\WebsiteConfiguratorBundle\RuleAssessor\RuleAssessorManager;

class WebsiteManager
{
    /**
     * @var WebsiteRegistryInterface
     */
    private $registry;

    /**
     * @var RuleAssessorManager
     */
    private $ruleAssessorManager;

    /**
     * @var WebsiteInterface
     */
    private $currentWebsite;

    /**
     * Constructor
     *
     * @param WebsiteRegistryInterface $registry
     * @param RuleAssessorManager    $ruleAssessorManager
     */
    public function __construct(
        WebsiteRegistryInterface $registry,
        RuleAssessorManager    $ruleAssessorManager
    )
    {
        $this->registry            = $registry;
        $this->ruleAssessorManager = $ruleAssessorManager;
        $this->currentWebsite        = null;
    }

    /**
     * Get current website
     *
     * @return WebsiteInterface|null
     */
    public function getCurrentWebsite()
    {
        return $this->currentWebsite;
    }

    /**
     * Returns full website theme template path
     *
     * @param string $path
     *
     * @return string the template path
     */
    public function getThemeTemplate($path)
    {
        if (!is_string($path)) {
            throw new UnexpectedTypeException($path, 'string');
        }

        return sprintf(
            '@%s/%s',
            WebsiteInterface::TEMPLATE_NAMESPACE,
            $path
        );
    }

    /**
     * Returns full website theme asset path
     *
     * @param string $path
     *
     * @return string the full website asset path
     */
    public function getThemeAsset($path)
    {
        if (!is_string($path)) {
            throw new UnexpectedTypeException($path, 'string');
        }

        return sprintf(
            '/themes/%s/%s',
            strtolower($this->currentWebsite->getAlias()),
            $path
        );
    }

    /**
     * Guess a website based on a Request
     *
     * @param Request $request.
     *
     * @return WebsiteInterface | null
     */
    public function guessWebsite(Request $request)
    {
        foreach ($this->registry->getWebsites() as $website) {
            if ($this->match($website, $request)) {
                $this->currentWebsite = $website;

                return $website;
            }
        }

        return null;
    }

    /**
     * Match website rules with the request.
     *
     * @param WebsiteInterface $website
     * @param Request $request
     *
     * @return boolean
     */
    public function match(WebsiteInterface $website, Request $request)
    {
        foreach ($website->getRules() as $rule) {
            foreach ($rule as $alias => $parameters) {
                if (!$this->ruleAssessorManager->match($alias, $request, $parameters)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Return the full namespaced current website template path
     *
     * @param string $templatePath
     *
     * @return string
     */
    public function getThemeNamespacedTemplatePath($templatePath)
    {
        if (null === $this->getCurrentWebsite()) {
            return $templatePath;
        }

        return sprintf(
            '@%s/%s',
            WebsiteInterface::TEMPLATE_NAMESPACE,
            $templatePath
        );
    }
}
