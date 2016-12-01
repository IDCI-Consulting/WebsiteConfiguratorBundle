<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\Website;

class Website implements WebsiteInterface
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $themePath;

    /**
     * @var array
     */
    private $rules;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $alias
     * @param string $themePath
     * @param array  $rules
     */
    public function __construct($name, $alias = null, $themePath, array $rules = array())
    {
        $this->name       = $name;
        $this->alias      = $alias;
        $this->themePath  = $themePath;
        $this->rules      = $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        if (null === $this->alias) {
            return $this->getName();
        }

        return $this->alias;
    }

    /**
     * {@inheritdoc}
     */
    public function getThemePath()
    {
        return $this->themePath;
    }

    /**
     * {@inheritdoc}
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * {@inheritdoc}
     */
    public function getThemeTemplatePath()
    {
        return sprintf('%s/views', $this->getThemePath());
    }

    /**
     * {@inheritdoc}
     */
    public function getThemeAssetsPath()
    {
        return sprintf('%s/public', $this->getThemePath());
    }
}