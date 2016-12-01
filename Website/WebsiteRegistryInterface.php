<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\Website;

interface WebsiteRegistryInterface
{
    /**
     * Sets a website identify by a alias.
     *
     * @param string           $alias  The alias.
     * @param WebsiteInterface $website  The website.
     *
     * @return WebsiteRegistryInterface
     */
    public function setWebsite($alias, WebsiteInterface $website);

    /**
     * Returns a website by alias.
     *
     * @param string $alias The alias of the website.
     *
     * @return WebsiteRegistryInterface.
     *
     * @throws Exception\UnexpectedTypeException  if the passed alias is not a string.
     * @throws Exception\InvalidArgumentException if the website can not be retrieved.
     */
    public function getWebsite($alias);

    /**
     * Returns all websites.
     *
     * @return array.
     */
    public function getWebsites();

    /**
     * Returns whether the given website is supported.
     *
     * @param string $alias The alias of the website.
     *
     * @return bool Whether the website is supported.
     */
    public function hasWebsite($alias);
}
