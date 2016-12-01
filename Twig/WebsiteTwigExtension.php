<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\Twig;

use IDCI\Bundle\WebsiteConfiguratorBundle\Website\WebsiteManager;

class WebsiteTwigExtension extends \Twig_Extension
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * Constructor
     *
     * @param WebsiteManager $websiteManager
     */
    public function __construct(WebsiteManager $websiteManager)
    {
        $this->websiteManager = $websiteManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'theme_path',
                array($this, 'themePath'),
                array('is_safe' => array('html', 'js'))
            ),
            new \Twig_SimpleFunction(
                'theme_asset',
                array($this, 'themeAsset'),
                array('is_safe' => array('html', 'js'))
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idci_website_configurator_twig_extension';
    }

    /**
     * Returns the full theme path
     *
     * @param string $path
     *
     * @return string
     */
    public function themePath($path)
    {
        return $this->websiteManager->getThemeTemplate($path);
    }

    /**
     * Returns the full theme asset path
     *
     * @param string $path
     *
     * @return string
     */
    public function themeAsset($path)
    {
        return $this->websiteManager->getThemeAsset($path);
    }
}
