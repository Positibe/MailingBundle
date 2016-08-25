<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MailingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class MailingCompilerPass
 * @package Positibe\Bundle\MailingBundle\DependencyInjection\Compiler
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MailingCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $sender = $container->getDefinition('positibe_mailing.sender.container');
        foreach ($container->findTaggedServiceIds('positibe.mailing.sender') as $id => $attributes) {
            $sender->addMethodCall('addSender', array(new Reference($id)));
        }

        $delivery = $container->getDefinition('positibe_mailing.mail_delivery');
        foreach ($container->findTaggedServiceIds('positibe.mailing.provider') as $id => $attributes) {
            $delivery->addMethodCall('addProvider', array(new Reference($id)));
        }
    }

} 