<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\EventSubscriber;

use Doctrine\Common\Annotations\Reader;
use IDCI\Bundle\WebsiteConfiguratorBundle\Website\WebsiteManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Bundle\TwigBundle\TwigEngine;
use IDCI\Bundle\WebsiteConfiguratorBundle\Annotation\ThemeTemplate;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * The ThemeTemplateEventSubscriber class parses @ThemeTemplate annotation blocks located in
 * controller classes.
 */
class ThemeTemplateEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var TwigEngine
     */
    private $templating;

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * Constructor
     *
     * @param Reader $reader
     * @param TwigEngine $templating
     * @param WebsiteManager $websiteManager
     */
    public function __construct(Reader $reader, TwigEngine $templating, WebsiteManager $websiteManager)
    {
        $this->reader = $reader;
        $this->templating = $templating;
        $this->websiteManager = $websiteManager;
    }

    /**
     * Modifies the Request object to apply configuration information found in
     * controllers annotations
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $className = class_exists('Doctrine\Common\Util\ClassUtils') ? ClassUtils::getClass($controller[0]) : get_class($controller[0]);
        $object = new \ReflectionClass($className);
        $method = $object->getMethod($controller[1]);

        $annotations = $this->reader->getMethodAnnotations($method);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof ThemeTemplate) {
                $request = $event->getRequest();

                $themeTemplatePath = $annotation->getTemplatePath();

                if (!$themeTemplatePath) {
                    throw new \Exception('Value for @ThemeTemplate annotation is not set');
                }

                $request->attributes->set('_theme_template_path', $annotation->getTemplatePath());
            }
        }
    }

    /**
     * Renders the template and initializes a new response object with the
     * rendered template content.
     *
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $themeTemplatePath = $request->attributes->get('_theme_template_path');

        if (null === $themeTemplatePath) {
            return;
        }

        $event->setResponse($this->templating->renderResponse(
            $this->websiteManager->getThemeNamespacedTemplatePath($themeTemplatePath),
            $event->getControllerResult()
        ));
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::VIEW => 'onKernelView',
        );
    }
}
