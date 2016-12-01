<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\WebsiteConfiguratorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use IDCI\Bundle\WebsiteConfiguratorBundle\DependencyInjection\Compiler\WebsiteCompilerPass;
use IDCI\Bundle\WebsiteConfiguratorBundle\DependencyInjection\Compiler\RuleAssessorCompilerPass;

class IDCIWebsiteConfiguratorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new WebsiteCompilerPass());
        $container->addCompilerPass(new RuleAssessorCompilerPass());
    }
}