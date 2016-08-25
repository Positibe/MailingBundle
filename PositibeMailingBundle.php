<?php

namespace Positibe\Bundle\MailingBundle;

use Positibe\Bundle\MailingBundle\DependencyInjection\Compiler\MailingCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PositibeMailingBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new MailingCompilerPass());
    }
}
