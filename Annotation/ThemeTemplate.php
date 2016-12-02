<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\Annotation;

/**
 * The ThemeTemplate class handles the ThemeTemplate annotation parts.
 *
 * @Annotation
 * @Target({"METHOD"})
 */
class ThemeTemplate
{
    private $templatePath;

    public function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            if (!method_exists($this, $name = 'set'.$key)) {
                throw new \RuntimeException(sprintf('Unknown key "%s" for annotation "@%s".', $key, get_class($this)));
            }

            $this->$name($value);
        }
    }

    /**
     * Sets the template logic name.
     *
     * @param string $template The template logic name
     */
    public function setValue($template)
    {
        $this->setTemplatePath($template);
    }

    /**
     * Sets the template path.
     *
     * @param string $templatePath
     */
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * Returns the template reference.
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }
}
