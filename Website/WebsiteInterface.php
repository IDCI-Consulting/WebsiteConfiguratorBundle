<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\Website;

interface WebsiteInterface
{
    const TEMPLATE_NAMESPACE = "theme";

    /**
     * Returns the website name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the website alias.
     *
     * @return string
     */
    public function getAlias();

    /**
     * Returns the theme files path.
     *
     * @return string
     */
    public function getThemePath();

    /**
     * Returns the rules.
     *
     * @return array
     */
    public function getRules();

    /**
     * Returns the theme template path
     *
     * @param string
     */
    public function getThemeTemplatePath();

    /**
     * Returns the theme assets path
     *
     * @param string
     */
    public function getThemeAssetsPath();
}