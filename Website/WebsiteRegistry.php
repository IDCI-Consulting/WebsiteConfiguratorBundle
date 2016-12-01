<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\Website;

use IDCI\Bundle\WebsiteConfiguratorBundle\Exception\UnexpectedTypeException;

class WebsiteRegistry implements WebsiteRegistryInterface
{
    /**
     * @var WebsiteInterface[]
     */
    private $websites = array();

    /**
     * {@inheritdoc}
     */
    public function setWebsite($alias, WebsiteInterface $website)
    {
        $this->websites[$alias] = $website;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getWebsite($alias)
    {
        if (!is_string($alias)) {
            throw new UnexpectedTypeException($alias, 'string');
        }

        if (!isset($this->websites[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load website "%s"', $alias));
        }

        return $this->websites[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function getWebsites()
    {
        return $this->websites;
    }

    /**
     * {@inheritdoc}
     */
    public function hasWebsite($alias)
    {
        if (!isset($this->websites[$alias])) {
            return false;
        }

        return true;
    }
}