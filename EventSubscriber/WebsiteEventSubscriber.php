<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use IDCI\Bundle\WebsiteConfiguratorBundle\Website\WebsiteManager;

class WebsiteEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var FilesystemLoader
     */
    private $twigLoader;

    /**
     * Constructor
     *
     * @param WebsiteManager     $websiteManager
     * @param FilesystemLoader $twigLoader
     */
    public function __construct(
        WebsiteManager     $websiteManager,
        FilesystemLoader $twigLoader
    )
    {
        $this->websiteManager = $websiteManager;
        $this->twigLoader   = $twigLoader;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(
                array('guessWebsite', 0)
            ),
            KernelEvents::RESPONSE => array(
                array('addWebsiteHeader', 0)
            )
        );
    }

    public function guessWebsite(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return false;
        }

        $request = $event->getRequest();
        $website   = $this->websiteManager->guessWebsite($request);

        if (null !== $website && null !== $website->getThemePath()) {
            // Add website theme path to twig loader
            $this->twigLoader->addPath(
                $website->getThemeTemplatePath(),
                $website::TEMPLATE_NAMESPACE
            );
        }
    }

    public function addWebsiteHeader(FilterResponseEvent $event)
    {
        if (null !== $this->websiteManager->getCurrentWebsite()) {
            $response = $event->getResponse();
            $response->headers->set('X-Website-Alias', $this->websiteManager->getCurrentWebsite()->getAlias());
            $response->headers->set('X-Website-Name', $this->websiteManager->getCurrentWebsite()->getName());
        }
    }
}
