<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle\RuleAssessor;

use Doctrine\Common\Util\Inflector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RequestRuleAssessor extends AbstractRuleAssessor
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'base_path'      => null,
                'base_url'       => null,
                'charsets'       => null,
                'client_ip'      => null,
                'client_ips'     => null,
                'content_type'   => null,
                'default_locale' => null,
                'http_host'      => null,
                'languages'      => null,
                'locale'         => null,
                'path_info'      => null,
                'port'           => null,
                'request_format' => null,
                'scheme'         => null,
                'script_name'    => null,
                'uri'            => null,
            ))
            ->setAllowedTypes('base_path',      array('null', 'string'))
            ->setAllowedTypes('base_url',       array('null', 'string'))
            ->setAllowedTypes('charsets',       array('null', 'array'))
            ->setAllowedTypes('client_ip',      array('null', 'string'))
            ->setAllowedTypes('client_ips',     array('null', 'array'))
            ->setAllowedTypes('content_type',   array('null', 'string'))
            ->setAllowedTypes('default_locale', array('null', 'string'))
            ->setAllowedTypes('http_host',      array('null', 'string'))
            ->setAllowedTypes('languages',      array('null', 'array'))
            ->setAllowedTypes('locale',         array('null', 'string'))
            ->setAllowedTypes('path_info',      array('null', 'string'))
            ->setAllowedTypes('port',           array('null', 'string'))
            ->setAllowedTypes('request_format', array('null', 'string'))
            ->setAllowedTypes('scheme',         array('null', 'string'))
            ->setAllowedTypes('script_name',    array('null', 'string'))
            ->setAllowedTypes('uri',            array('null', 'string'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function doMatch(Request $request, array $parameters = array())
    {
        foreach ($parameters as $key => $regex) {
            if (null !== $regex) {
                $getter = sprintf(
                    'get%s',
                    Inflector::classify($key)
                );

                $requestValue = call_user_func_array(
                    array($request, $getter),
                    array()
                );

                return 1 === preg_match('/' . $regex . '/', $requestValue);
            }
        }
    }
}
