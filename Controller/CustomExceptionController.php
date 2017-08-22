<?php

namespace IDCI\Bundle\WebsiteConfiguratorBundle\Controller;

use IDCI\Bundle\WebsiteConfiguratorBundle\Website\WebsiteManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Twig\Environment;

class CustomExceptionController extends ExceptionController
{
    /**
     * @var WebsiteManager
     **/
    private $websiteManager;

    public function __construct(Environment $twig, $debug, WebsiteManager $websiteManager)
    {
        parent::__construct($twig, $debug);
        $this->websiteManager = $websiteManager;
    }

    protected function findTemplate(Request $request, $format, $code, $showException)
    {
        $name = $showException ? 'exception' : 'error';
        if ($showException && 'html' == $format) {
            $name = 'exception_full';
        }

        $currentWebsite = $this->websiteManager->guessWebsite($request);

        if (null === $currentWebsite) {
            return parent::findTemplate($request, $format, $code, $showException);
        }

        // For error pages, try to find a template for the specific HTTP status code and format
        if (!$showException) {
            $template = $this->websiteManager->getThemeNamespacedTemplatePath(sprintf('Exception/%s%s.%s.twig', $name, $code, $format));
            if ($this->templateExists($template)) {
                return $template;
            }
        }

        // try to find a template for the given format
        $template = $this->websiteManager->getThemeNamespacedTemplatePath(sprintf('Exception/%s.%s.twig', $name, $format));
        if ($this->templateExists($template)) {
            return $template;
        }

        return parent::findTemplate($request, $format, $code, $showException);
    }
}
